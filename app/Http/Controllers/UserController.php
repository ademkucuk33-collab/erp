<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->isSuperAdmin(), 403);
        return view('users.index', [
            'users' => User::with('warehouse')->latest('id')->paginate(30),
            'warehouses' => Warehouse::where('active', true)->orderBy('code')->get(),
        ]);
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->isSuperAdmin(), 403);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'role' => ['required', Rule::in(['warehouse_manager', 'warehouse_staff'])],
            'warehouse_id' => ['required', 'integer', Rule::exists('warehouses', 'id')->where('active', true)],
            'password' => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()->symbols()],
        ]);
        User::create($data);
        return redirect()->route('users.index')->with('success', 'Depo kullanıcısı oluşturuldu. Şifre yalnızca yetkili personelle güvenli kanaldan paylaşılmalı.');
    }
}
