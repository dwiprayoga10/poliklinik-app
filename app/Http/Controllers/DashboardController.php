<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Poli;
use App\Models\Obat;
use App\Models\JadwalPeriksa;
use App\Models\Periksa;
use App\Models\DaftarPoli;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total data
        $totalDokter = User::where('role', 'dokter')->count();
        $totalPasien = User::where('role', 'pasien')->count();
        $totalPoli = Poli::count();
        $totalObat = Obat::count();
        $totalJadwal = JadwalPeriksa::count();
        $totalPeriksa = Periksa::count();
        $totalDaftarPoli = DaftarPoli::count();

        // Ambil data grafik pendaftaran poli per bulan
        $pendaftaran = DaftarPoli::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $bulan = $pendaftaran->pluck('bulan')->map(function ($b) {
            return date('F', mktime(0, 0, 0, $b, 1));
        });

        $jumlah = $pendaftaran->pluck('jumlah');

        return view('admin.dashboard', compact(
            'totalDokter',
            'totalPasien',
            'totalPoli',
            'totalObat',
            'totalJadwal',
            'totalPeriksa',
            'totalDaftarPoli',
            'bulan',
            'jumlah'
        ));
    }
}
