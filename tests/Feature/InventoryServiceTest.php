<?php

namespace Tests\Feature;

use App\Models\ChannelAccount;
use App\Models\InventoryBalance;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Services\InventoryService;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryServiceTest extends TestCase
{
    use RefreshDatabase;

    private function warehouse(): Warehouse
    {
        return Warehouse::create(['code' => 'DE-01', 'name' => 'Almanya Deposu', 'country_code' => 'DE']);
    }

    private function product(): Product
    {
        return Product::create(['sku' => 'IZ-001', 'name' => '3D Panel', 'cost_currency' => 'EUR']);
    }

    public function test_stock_adjustment_is_idempotent_and_never_negative(): void
    {
        $w = $this->warehouse(); $p = $this->product(); $inventory = app(InventoryService::class);
        $inventory->adjust($w->id, $p->id, 10, 'batch:abc');
        $inventory->adjust($w->id, $p->id, 10, 'batch:abc');
        $this->assertSame(10, InventoryBalance::first()->on_hand);
        $this->assertSame(1, StockMovement::count());
        $this->expectException(DomainException::class);
        $inventory->adjust($w->id, $p->id, -11, 'batch:def');
    }

    public function test_reservation_is_atomic_replay_safe_and_fulfillment_decrements_stock(): void
    {
        $w = $this->warehouse(); $p = $this->product(); $inventory = app(InventoryService::class);
        $inventory->adjust($w->id, $p->id, 10, 'initial:001');
        $account = ChannelAccount::create(['name'=>'Amazon DE','platform'=>'amazon']);
        $order = Order::create(['channel_account_id'=>$account->id,'external_order_id'=>'A1','warehouse_id'=>$w->id,'destination_country'=>'DE','currency'=>'EUR']);
        OrderItem::create(['order_id'=>$order->id,'product_id'=>$p->id,'external_sku'=>'AMZ-PANEL','quantity'=>4,'unit_price_minor'=>2500]);
        $inventory->reserveOrder($order);
        $inventory->reserveOrder($order);
        $balance = InventoryBalance::first();
        $this->assertSame(10, $balance->on_hand);
        $this->assertSame(4, $balance->reserved);
        // This service is only to be invoked by a future shipment state machine after carrier confirmation.
        $inventory->fulfillOrder($order);
        $inventory->fulfillOrder($order);
        $balance->refresh();
        $this->assertSame(6, $balance->on_hand);
        $this->assertSame(0, $balance->reserved);
        $this->assertSame('shipped', $order->fresh()->status);
    }

    public function test_insufficient_stock_does_not_leave_partial_reservations(): void
    {
        $w=$this->warehouse(); $p=$this->product(); $inventory=app(InventoryService::class);
        $inventory->adjust($w->id, $p->id, 1, 'initial:001');
        $account = ChannelAccount::create(['name'=>'Amazon DE','platform'=>'amazon']);
        $order = Order::create(['channel_account_id'=>$account->id,'external_order_id'=>'A1','warehouse_id'=>$w->id,'destination_country'=>'DE','currency'=>'EUR']);
        OrderItem::create(['order_id'=>$order->id,'product_id'=>$p->id,'external_sku'=>'AMZ-PANEL','quantity'=>2]);
        try { $inventory->reserveOrder($order); $this->fail('Expected stock shortage'); }
        catch (DomainException $e) { $this->assertSame(0, InventoryBalance::first()->reserved); $this->assertSame('new', $order->fresh()->status); }
    }
}
