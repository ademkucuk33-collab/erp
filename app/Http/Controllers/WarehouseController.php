<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $warehouses = Warehouse::query()->when(!$user->isSuperAdmin(), fn ($q) => $q->whereKey($user->warehouse_id ?? 0))->orderBy('code')->get();
        return view('warehouses.index', compact('warehouses'));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->isSuperAdmin(), 403);
        $data = $request->validate([
            'code' => ['required', 'string', 'max:30', 'alpha_dash', Rule::unique('warehouses', 'code')],
            'name' => ['required', 'string', 'max:255'],
            'country_code' => ['required', 'string', 'size:2', 'regex:/^[A-Za-z]{2}$/'],
            'address' => ['nullable', 'string', 'max:2000'],
        ]);
        $data['country_code'] = strtoupper($data['country_code']);
        Warehouse::create($data);
        return redirect()->route('warehouses.index')->with('success', 'Depo oluşturuldu.');
    }
}
