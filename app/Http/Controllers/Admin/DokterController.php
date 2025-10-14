<?php

namespace App\Http\Controllers\Admin;

use App\Models\Poli;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class DokterController extends Controller
{
   public function index()
    {
        $dokters = User::where('role', 'dokter')->with('poli')->get();
        return view('admin.dokter.index', compact('dokters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $polis = Poli::all();
        return view('admin.dokter.create', compact('polis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp',
            'no_hp' => 'required|string|max:15',
            'id_poli' => 'required|exists:poli,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'id_poli' => $request->id_poli,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dokter',
        ]);

        return redirect()->route('dokter.index')
            ->with('message', 'Dokter berhasil ditambahkan.')
            ->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $dokter)
    {
        $polis = Poli::all();
        return view('admin.dokter.edit', compact('dokter', 'polis'));
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, User $dokter)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            // FIX: Mengabaikan ID dokter saat ini (.$dokter->id) agar KTP lama bisa digunakan
            'no_ktp' => 'required|string|max:16|unique:users,no_ktp,' . $dokter->id,
            'no_hp' => 'required|string|max:15',
            'id_poli' => 'required|exists:poli,id', 
            // FIX: Menggunakan 'email' dan mengabaikan ID dokter saat ini
            'email' => 'required|email|unique:users,email,' . $dokter->id,
            'password' => 'nullable|min:6',
        ]);

        $dokter->nama = $request->nama;
        $dokter->alamat = $request->alamat;
        $dokter->no_ktp = $request->no_ktp;
        $dokter->no_hp = $request->no_hp;
        $dokter->id_poli = $request->id_poli;
        $dokter->email = $request->email;

        //update password bila password disii
        if ($request->filled('password')) {
            $dokter->password = Hash::make($request->password);
        }

        //disimpan
        $dokter->save();

        // Mengubah 'success' menjadi 'message' agar konsisten dengan format flash message
        return redirect()->route('dokter.index')->with('message', 'Data Dokter Berhasil di ubah')->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $dokter)
    {
        $dokter->delete();
        // Mengubah 'success' menjadi 'message' agar konsisten dengan format flash message
        return redirect()->route('dokter.index')->with('message', 'Data Dokter Berhasil dihapus')->with('type', 'success');
    }
}
