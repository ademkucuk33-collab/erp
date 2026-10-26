<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $products = Product::query()
            ->when(!$user->isSuperAdmin(), fn ($query) => $query->whereHas('balances', fn ($b) => $b->where('warehouse_id', $user->warehouse_id ?? 0)))
            ->withSum(['balances as total_on_hand' => fn ($query) => $query->when(!$user->isSuperAdmin(), fn ($q) => $q->where('warehouse_id', $user->warehouse_id ?? 0))], 'on_hand')
            ->withSum(['balances as total_reserved' => fn ($query) => $query->when(!$user->isSuperAdmin(), fn ($q) => $q->where('warehouse_id', $user->warehouse_id ?? 0))], 'reserved')
            ->latest('id')->paginate(25);
        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->isSuperAdmin(), 403);
        $data = $request->validate([
            'sku' => ['required', 'string', 'max:100', Rule::unique('products', 'sku')],
            'name' => ['required', 'string', 'max:255'],
            'barcode' => ['nullable', 'string', 'max:160'],
            'weight_g' => ['nullable', 'integer', 'min:0'],
            'cost_minor' => ['nullable', 'integer', 'min:0'],
            'cost_currency' => ['required', Rule::in(['EUR', 'GBP', 'TRY'])],
        ]);
        Product::create($data);
        return redirect()->route('products.index')->with('success', 'Ürün oluşturuldu.');
    }
}
