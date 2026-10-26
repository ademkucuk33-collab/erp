<?php

namespace App\Services;

use App\Models\InventoryBalance;
use App\Models\Order;
use App\Models\StockMovement;
use App\Models\StockReservation;
use DomainException;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /** Adjust physical stock once per stable reference. Replays are no-ops. */
    public function adjust(int $warehouseId, int $productId, int $delta, string $referenceKey, ?int $userId = null): void
    {
        DB::transaction(function () use ($warehouseId, $productId, $delta, $referenceKey, $userId) {
            if (StockMovement::where('reference_key', $referenceKey)->exists()) {
                return;
            }
            // Unique balance row must exist before locking; created on product/warehouse setup or with insert-or-ignore.
            DB::table('inventory_balances')->insertOrIgnore([
                'warehouse_id' => $warehouseId, 'product_id' => $productId,
                'on_hand' => 0, 'reserved' => 0, 'created_at' => now(), 'updated_at' => now(),
            ]);
            $balance = InventoryBalance::where('warehouse_id', $warehouseId)->where('product_id', $productId)->lockForUpdate()->firstOrFail();
            $newOnHand = $balance->on_hand + $delta;
            if ($newOnHand < $balance->reserved || $newOnHand < 0) {
                throw new DomainException('Stok, ayrılmış ürün sayısının altına indirilemez.');
            }
            $balance->update(['on_hand' => $newOnHand]);
            StockMovement::create([
                'warehouse_id' => $warehouseId, 'product_id' => $productId, 'user_id' => $userId,
                'delta' => $delta, 'reason' => 'manual_adjustment', 'reference_key' => $referenceKey,
            ]);
        }, 3);
    }

    /** Reserve all lines atomically; duplicate calls do not reserve twice. */
    public function reserveOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($order->status === 'reserved') {
                return;
            }
            if ($order->status !== 'new' || !$order->warehouse_id) {
                throw new DomainException('Sipariş rezerve edilebilir durumda değil veya deposu seçilmemiş.');
            }
            $items = $order->items()->orderBy('product_id')->orderBy('id')->get();
            if ($items->isEmpty() || $items->contains(fn ($item) => !$item->product_id || $item->quantity < 1)) {
                throw new DomainException('Ürün eşleştirmesi eksik veya sipariş satırları geçersiz.');
            }
            $grouped = $items->groupBy('product_id');
            foreach ($grouped as $productId => $productItems) {
                $balance = InventoryBalance::where('warehouse_id', $order->warehouse_id)
                    ->where('product_id', $productId)->lockForUpdate()->first();
                $needed = $productItems->sum('quantity');
                if (!$balance || $balance->available() < $needed) {
                    throw new DomainException("Yetersiz stok. Ürün: {$productId}");
                }
                $balance->increment('reserved', $needed);
            }
            foreach ($items as $item) {
                StockReservation::create([
                    'order_item_id' => $item->id, 'warehouse_id' => $order->warehouse_id,
                    'product_id' => $item->product_id, 'quantity' => $item->quantity, 'status' => 'active',
                ]);
            }
            $order->update(['status' => 'reserved']);
        }, 3);
    }

    /** Release reservation, without adding inventory that was never removed. */
    public function releaseOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($order->status === 'cancelled') { return; }
            if ($order->status !== 'reserved') { throw new DomainException('Yalnızca rezerve edilmiş sipariş serbest bırakılabilir.'); }
            $reservations = StockReservation::whereIn('order_item_id', $order->items()->pluck('id'))->where('status', 'active')->orderBy('product_id')->get();
            foreach ($reservations->groupBy('product_id') as $productId => $rows) {
                $balance = InventoryBalance::where('warehouse_id', $order->warehouse_id)->where('product_id', $productId)->lockForUpdate()->firstOrFail();
                $balance->decrement('reserved', $rows->sum('quantity'));
            }
            StockReservation::whereIn('id', $reservations->pluck('id'))->update(['status' => 'released']);
            $order->update(['status' => 'cancelled']);
        }, 3);
    }

    /** Ship only after successful carrier creation is verified; future shipment state machine will call this. */
    public function fulfillOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($order->status === 'shipped') { return; }
            if ($order->status !== 'reserved') { throw new DomainException('Önce stok rezerve edilmeli.'); }
            $reservations = StockReservation::whereIn('order_item_id', $order->items()->pluck('id'))->where('status', 'active')->orderBy('product_id')->get();
            if ($reservations->count() !== $order->items()->count()) { throw new DomainException('Eksik rezervasyon.'); }
            foreach ($reservations->groupBy('product_id') as $productId => $rows) {
                $qty = $rows->sum('quantity');
                $balance = InventoryBalance::where('warehouse_id', $order->warehouse_id)->where('product_id', $productId)->lockForUpdate()->firstOrFail();
                if ($balance->reserved < $qty || $balance->on_hand < $qty) { throw new DomainException('Stok tutarsızlığı.'); }
                $balance->decrement('reserved', $qty);
                $balance->decrement('on_hand', $qty);
                StockMovement::create([
                    'warehouse_id' => $order->warehouse_id, 'product_id' => $productId,
                    'delta' => -$qty, 'reason' => 'shipment',
                    'reference_key' => "order-shipped:{$order->id}:product:{$productId}",
                ]);
            }
            StockReservation::whereIn('id', $reservations->pluck('id'))->update(['status' => 'fulfilled']);
            $order->update(['status' => 'shipped']);
        }, 3);
    }
}
