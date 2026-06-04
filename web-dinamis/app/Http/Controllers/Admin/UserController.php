<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
  public function index(Request $request)
{
    // Ambil SEMUA user pelanggan seperti semula agar peringkat tidak bergeser
    $users = User::where('role', 'user')
        ->leftJoin('orders', function($join) {
            $join->on('users.id', '=', 'orders.user_id')
                 ->where('orders.status_payment', '=', 'paid');
        })
        ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
        ->select(
            'users.id',
            'users.name',
            'users.email',
            'users.phone',
            'users.is_blocked',
            'users.created_at',
            DB::raw('IFNULL(SUM(order_items.qty), 0) as total_checkout')
        )
        ->groupBy('users.id', 'users.name', 'users.email', 'users.phone', 'users.is_blocked', 'users.created_at')
        ->orderBy('total_checkout', 'desc')
        ->get();

    // Logika Validasi Pencarian
    if ($request->has('search') && !empty($request->get('search'))) {
        $search = strtolower($request->get('search'));
        
        // Cek apakah ada nama atau email di dalam koleksi $users yang cocok
        $isFound = $users->contains(function ($user) use ($search) {
            return str_contains(strtolower($user->name), $search) || 
                   str_contains(strtolower($user->email), $search);
        });

        // Jika keyword dicari tapi tidak ada satupun yang cocok di list
        if (!$isFound) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Akun tidak terdaftar atau nama/email salah!');
        }
    }

    return view('admin.users.index', compact('users'));
}

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role' => 'user',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function toggleBlock($id)
    {
        $user = User::findOrFail($id);
        $user->is_blocked = !$user->is_blocked;
        $user->save();
        
        $status = $user->is_blocked ? 'diblokir' : 'dibuka blokirnya';
        return back()->with('success', "Akun user berhasil {$status}.");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}