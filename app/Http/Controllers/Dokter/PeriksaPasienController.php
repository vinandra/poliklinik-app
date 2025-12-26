<?php

namespace App\Http\Controllers\Dokter;

use App\Models\DaftarPoli;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksa'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id)
    {
        $obats = Obat::all();
        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_daftar_poli' => 'required|exists:daftar_poli,id',
            'obat_json' => 'required',
            'catatan' => 'nullable|string',
            'biaya_periksa' => 'required|integer',
        ]);

        $obatIds = collect(json_decode($request->obat_json, true))->filter()->values();

        if ($obatIds->isEmpty()) {
            return back()->with('type', 'danger')->with('message', 'Pilih minimal satu obat.');
        }

        try {
            DB::transaction(function () use ($request, $obatIds) {
                // Lock stok obat untuk mencegah race condition
                $obats = Obat::whereIn('id', $obatIds)->lockForUpdate()->get()->keyBy('id');

                foreach ($obatIds as $idObat) {
                    $obat = $obats->get($idObat);

                    if (!$obat) {
                        throw new \RuntimeException('Obat tidak ditemukan.');
                    }

                    if ($obat->stok <= 0) {
                        throw new \RuntimeException("Stok obat {$obat->nama_obat} habis.");
                    }
                }

                $periksa = Periksa::create([
                    'id_daftar_poli' => $request->id_daftar_poli,
                    'tgl_periksa' => now(),
                    'catatan' => $request->catatan,
                    'biaya_periksa' => $request->biaya_periksa + 150000,
                ]);

                foreach ($obatIds as $idObat) {
                    $obat = $obats->get($idObat);
                    $obat->decrement('stok', 1);

                    DetailPeriksa::create([
                        'id_periksa' => $periksa->id,
                        'id_obat' => $idObat,
                    ]);
                }
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('type', 'danger')->with('message', $e->getMessage());
        }

        return redirect()->route('periksa-pasien.index')->with('message', 'Data periksa berhasil disimpan dan stok diperbarui.')->with('type', 'success');
    }


}
