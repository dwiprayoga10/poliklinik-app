<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriksaPasienController extends Controller
{
    /**
     * LIST PASIEN YANG AKAN DIPERIKSA
     */
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($q) use ($dokterId) {
                $q->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    /**
     * FORM PERIKSA PASIEN
     */
    public function create($id)
    {
        // ✅ AMBIL SEMUA OBAT (TERMASUK STOK 0)
        $obats = Obat::orderBy('nama_obat')->get();

        return view('dokter.periksa-pasien.create', [
            'id'    => $id,
            'obats' => $obats
        ]);
    }

    /**
     * SIMPAN DATA PERIKSA
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_daftar_poli' => 'required|exists:daftar_poli,id',
            'obat_json'      => 'required',
            'catatan'        => 'nullable|string',
            'biaya_periksa'  => 'required|integer|min:0',
        ]);

        $obatIds = json_decode($request->obat_json, true);

        if (!is_array($obatIds) || count($obatIds) === 0) {
            return back()
                ->withErrors(['obat' => 'Obat belum dipilih'])
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $obatIds) {

                // ===============================
                // VALIDASI & PENGURANGAN STOK
                // ===============================
                foreach ($obatIds as $idObat) {
                    $obat = Obat::lockForUpdate()->findOrFail($idObat);

                    if ($obat->stok <= 0) {
                        throw new \Exception(
                            "Stok obat {$obat->nama_obat} sudah habis"
                        );
                    }

                    $obat->decrement('stok');
                }

                // ===============================
                // SIMPAN PERIKSA
                // ===============================
                $periksa = Periksa::create([
                    'id_daftar_poli' => $request->id_daftar_poli,
                    'tgl_periksa'    => now(),
                    'catatan'        => $request->catatan,
                    'biaya_periksa'  => $request->biaya_periksa + 150000,
                ]);

                // ===============================
                // DETAIL PERIKSA
                // ===============================
                foreach ($obatIds as $idObat) {
                    DetailPeriksa::create([
                        'id_periksa' => $periksa->id,
                        'id_obat'    => $idObat,
                    ]);
                }
            });

        } catch (\Exception $e) {
            return back()
                ->withErrors(['obat' => $e->getMessage()])
                ->withInput();
        }

        return redirect()
            ->route('periksa-pasien.index')
            ->with('success', 'Data periksa berhasil disimpan.');
    }
}
