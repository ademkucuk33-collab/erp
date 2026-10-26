<?php

namespace App\Http\Controllers;

use App\Models\InventoryBalance;
use App\Models\Order;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $orders = Order::query();
        $balances = InventoryBalance::query();
        if (!$user->isSuperAdmin()) {
            $orders->where('warehouse_id', $user->warehouse_id ?? 0);
            $balances->where('warehouse_id', $user->warehouse_id ?? 0);
        }
        return view('dashboard', [
            'orderCount' => (clone $orders)->count(),
            'newOrders' => (clone $orders)->where('status', 'new')->count(),
            'reservedOrders' => (clone $orders)->where('status', 'reserved')->count(),
            'lowStock' => (clone $balances)->whereRaw('(on_hand - reserved) <= 5')->count(),
            'productCount' => $user->isSuperAdmin() ? Product::count() : Product::whereHas('balances', fn ($q) => $q->where('warehouse_id', $user->warehouse_id ?? 0))->count(),
            'warehouseCount' => $user->isSuperAdmin() ? Warehouse::count() : ($user->warehouse_id ? 1 : 0),
            'recentOrders' => $orders->with('channelAccount', 'warehouse')->latest('id')->limit(8)->get(),
        ]);
    }
}
