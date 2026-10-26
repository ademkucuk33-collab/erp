<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\InventoryService;
use DomainException;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private function visibleOrders(Request $request)
    {
        return Order::query()->when(!$request->user()->isSuperAdmin(),
            fn ($query) => $query->where('warehouse_id', $request->user()->warehouse_id ?? 0));
    }

    public function index(Request $request)
    {
        $orders = $this->visibleOrders($request)->with('warehouse', 'channelAccount')
            ->when($request->input('status'), fn ($query, $status) => $query->where('status', $status))
            ->orderByDesc('placed_at')->orderByDesc('id')->paginate(30)->withQueryString();
        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order)
    {
        abort_unless($request->user()->canAccessWarehouse($order->warehouse_id), 403);
        $order->load('items.product', 'channelAccount', 'warehouse');
        return view('orders.show', compact('order'));
    }

    public function reserve(Request $request, Order $order, InventoryService $inventory)
    {
        abort_unless($request->user()->isSuperAdmin() || ($request->user()->role === 'warehouse_manager' && $request->user()->canAccessWarehouse($order->warehouse_id)), 403);
        try {
            $inventory->reserveOrder($order);
        } catch (DomainException $exception) {
            return back()->withErrors(['order' => $exception->getMessage()]);
        }
        return back()->with('success', 'Sipariş stoğu ayrıldı.');
    }
}
