<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(){
        return view('auth.login');
    }

    public function showRegister(){
        return view('auth.register');
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if($user->role == 'admin') {
                return redirect()->route(('admin.dashboard'));
            } elseif($user->role == 'dokter') {
                return redirect()->route(('dokter.dashboard'));
            } else {
                return redirect()->route(('pasien.dashboard'));
            }
        }

        return redirect()->back()->withErrors(['loginError' => 'Invalid email or password.']);
    }

        public function register(Request $request){
        $validatedData = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'no_ktp' => ['required', 'string', 'max:30'], 
            'no_hp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed'],
        ]);

        // Gunakan User::create dan Hash::make()
        User::create([
            'nama' => $validatedData['nama'],
            'alamat' => $validatedData['alamat'],
            'no_ktp' => $validatedData['no_ktp'],
            'no_hp' => $validatedData['no_hp'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), 
            'role' => 'pasien', 
        ]);

        return redirect()->route('login'); 
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
