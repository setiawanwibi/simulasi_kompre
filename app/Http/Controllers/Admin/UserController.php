<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if (!Auth::check() || Auth::user()->role != 'super_admin') {
                abort(403);
            }

            return $next($request);
        });
    }


    public function index()
    {
        $users = User::whereIn('role', ['admin','driver'])->get();

        return view('admin.akun.index', compact('users'));
    }


    public function create()
    {
        return view('admin.akun.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,driver'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return redirect()
            ->route('admin.akun.index')
            ->with('success', 'Akun berhasil ditambahkan');
    }


    public function edit($id)
    {
        $user = User::whereIn('role', ['admin','driver'])->findOrFail($id);

        return view('admin.akun.edit', compact('user'));
    }


    public function update(Request $request, $id)
    {
        $user = User::whereIn('role', ['admin','driver'])->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()
            ->route('admin.akun.index')
            ->with('success','Akun berhasil diperbarui');
    }


    public function destroy($id)
    {
        $user = User::whereIn('role', ['admin','driver'])->findOrFail($id);

        if ($user->id == Auth::id()) {
            return redirect()
                ->route('admin.akun.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        $user->delete();

        return redirect()
            ->route('admin.akun.index')
            ->with('success', 'Akun berhasil dihapus');
    }

}