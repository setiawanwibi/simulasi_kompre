<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password_lama' => 'nullable|required_with:password_baru',
            'password_baru' => 'nullable|min:6|confirmed',
        ],[
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',

            'password_lama.required_with' => 'Password lama wajib diisi jika ingin mengganti password',
            'password_baru.min' => 'Password baru minimal 6 karakter',
            'password_baru.confirmed' => 'Konfirmasi password baru tidak sama',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if($request->filled('password_baru')){

            if(!Hash::check($request->password_lama, $user->password)){
                return back()
                    ->with('error','Password lama tidak sesuai')
                    ->withInput();
            }

            $user->password = Hash::make($request->password_baru);
        }

        $user->save();

        return back()->with('success','Profil berhasil diperbarui');
    }
}
