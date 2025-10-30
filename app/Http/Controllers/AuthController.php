<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // dd($user->role);
            if ($user->role == 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role == 'dokter') {
                return redirect()->route('dokter.dashboard');
            } else {
                return redirect()->route('pasien.dashboard');
            }
            // dd($user);
        }

        // dd($user->role);
        return back()->withErrors(['email' => 'Email atau Password Salah !']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            // 'email' => ['required', 'string', 'email', 'max:255','unique:', User::class],
            'alamat' => ['required', 'string', 'max:255'],
            'no_ktp' => ['required', 'string', 'max:30'],
            'no_hp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed'],
        ]);


        //cek apakah nomor KTP sudah terdaftar
        if(User::where('no_ktp', $request->no_ktp)->exists()) {
            return back()->withErrors(['no_ktp' => 'Nomor Ktp Sudah terdaftar']);
        }

        $no_rm = date('Ym') . '-' . str_pad(
            User::where('no_rm', 'like', date('Ym') . '-%')->count() + 1,
            3,
            '0',
            STR_PAD_LEFT
        );

        /**
         * Ym menghasilkan string tahun dan bulan
         * User::where('no_rm', 'like', date('Ym') . '-%')->count() + 1, : menghitung berapa banyak pasien yang sudah punya no_rm dengan prefik bulan ini dan + 1 agar nomor berikutnya jadi urutan ke 6
         * 
         * 4. str_pad(..., 3, '0', STR_PAD_LEFT)
         * Menambahkan nol di depan agar hasilnya selalu 3 digit.
         * output : 202509-006
         * */ 

        User::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'no_rm' => $no_rm,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pasien',
        ]);

        return redirect()->route('login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function dokter()
    {
        $data = Poli::with('dokters')->get();
        return $data;
    }
}
