<?php

namespace App\Http\Controllers\Dokter;

use Illuminate\Http\Request;
use App\Models\JadwalPeriksa;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JadwalPriksaController extends Controller
{
    public function index()
    {
        $dokter = Auth::user();
    
        $jadwalPeriksas = JadwalPeriksa::where('id_dokter', $dokter->id)->orderBy('hari')->get();
        return view('dokter.jadwal-periksa.index', compact('jadwalPeriksas'));
    }

    public function create()
    {
        return view('dokter.jadwal-periksa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required'
        ]);

        JadwalPeriksa::create([
            'id_dokter' => Auth::id(),
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('jadwal-periksa.index')->with('message', 'Jadwal Periksa Berhasil disimpan')->with('type', 'success');
    }

    public function edit($id)
    {
        $jadwalPeriksa = JadwalPeriksa::findOrFail($id);
        return view('dokter.jadwal-periksa.edit', compact('jadwalPeriksa'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required'
        ]);

        $jadwalPeriksas = JadwalPeriksa::findOrFail($id);
        $jadwalPeriksas->update([
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('jadwal-periksa.index')->with('message', 'Jadwal Periksa Berhasil diubah')->with('type', 'success');
    }

    public function destroy(string $id){
        $jadwalPeriksas = JadwalPeriksa::findOrFail($id);
        $jadwalPeriksas->delete();

        return redirect()->route('jadwal-periksa.index')->with('message', 'Jadwal Periksa Berhasil dihapus')->with('type', 'success');
    }
}


