<x-layouts.app title="Detail Riwayat Pasien">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Detail Riwayat Pasien</h2>
                        <p class="text-muted mb-0">Informasi pemeriksaan dan hasil tindakan</p>
                    </div>
                    <a href="{{ route('dokter.riwayat-pasien.index') }}"
                       class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <!-- Informasi Pasien -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-4">Informasi Pasien</h5>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="text-muted small">Nama Pasien</div>
                                <div class="fw-semibold">
                                    {{ $periksa->daftarPoli->pasien->nama }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="text-muted small">No. Antrian</div>
                                <div class="fw-semibold">
                                    {{ $periksa->daftarPoli->no_antrian }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="text-muted small">Poli</div>
                                <div class="fw-semibold">
                                    {{ $periksa->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="text-muted small">Dokter</div>
                                <div class="fw-semibold">
                                    {{ $periksa->daftarPoli->jadwalPeriksa->dokter->nama }}
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="text-muted small">Keluhan</div>
                                <div class="fw-semibold">
                                    {{ $periksa->daftarPoli->keluhan }}
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="text-muted small">Tanggal Periksa</div>
                                <div class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catatan Dokter -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-3">Catatan Dokter</h5>
                        <p class="mb-0 text-muted">
                            {{ $periksa->catatan ?: 'Tidak ada catatan' }}
                        </p>
                    </div>
                </div>

                <!-- Obat -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-3">Obat yang Diresepkan</h5>

                        @if($periksa->detailPeriksas && $periksa->detailPeriksas->count() > 0)
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="text-muted small">
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Obat</th>
                                            <th class="text-end">Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($periksa->detailPeriksas as $index => $detail)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="fw-semibold">
                                                    {{ $detail->obat->nama_obat }}
                                                </td>
                                                <td class="text-end">
                                                    Rp {{ number_format($detail->obat->harga, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">
                                Tidak ada obat yang diresepkan
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Total Biaya -->
                <div class="card border-0 shadow-lg rounded-4 mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="text-muted mb-1">Total Biaya Periksa</div>
                        <h2 class="fw-bold text-primary mb-0">
                            Rp {{ number_format($periksa->biaya_periksa, 0, ',', '.') }}
                        </h2>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts.app>
