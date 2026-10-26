<?php

namespace App\Http\Controllers;

use App\Models\InventoryBalance;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\InventoryService;
use DomainException;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $balances = InventoryBalance::with('product', 'warehouse')
            ->when(!$user->isSuperAdmin(), fn ($q) => $q->where('warehouse_id', $user->warehouse_id ?? 0))
            ->orderBy('warehouse_id')->orderBy('product_id')->paginate(30);
        return view('stock', [
            'balances' => $balances,
            'warehouses' => $user->isSuperAdmin() ? Warehouse::where('active', true)->orderBy('code')->get() : collect(),
            'products' => $user->isSuperAdmin() ? Product::where('active', true)->orderBy('sku')->get() : collect(),
        ]);
    }

    public function adjust(Request $request, InventoryService $inventory)
    {
        abort_unless($request->user()->isSuperAdmin(), 403);
        $data = $request->validate([
            'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'delta' => ['required', 'integer', 'between:-1000000,1000000', 'not_in:0'],
            'reference_key' => ['required', 'string', 'max:150', 'regex:/^[a-zA-Z0-9:\-]+$/'],
        ]);
        try {
            $inventory->adjust($data['warehouse_id'], $data['product_id'], $data['delta'], $data['reference_key'], $request->user()->id);
        } catch (DomainException $exception) {
            return back()->withErrors(['delta' => $exception->getMessage()]);
        }
        return redirect()->route('stock.index')->with('success', 'Stok hareketi kaydedildi.');
    }
}
