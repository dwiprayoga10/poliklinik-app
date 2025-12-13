<x-layouts.app title="Riwayat Pasien">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-12">

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="fw-bold text-dark mb-0">
                        Riwayat Pasien
                    </h1>
                </div>

                <!-- Card -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light">
                                    <tr class="text-muted text-uppercase small">
                                        <th>No RM</th>
                                        <th>No Antrian</th>
                                        <th>Nama Pasien</th>
                                        <th>Keluhan</th>
                                        <th>Tanggal Periksa</th>
                                        <th>Biaya</th>
                                        <th class="text-center" style="width: 140px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($riwayatPasien as $riwayat)
                                        <tr class="border-bottom">

                                            <!-- No Rekam Medis -->
                                            <td>
                                                <span class="badge bg-secondary-subtle text-secondary fw-semibold px-3 py-2 rounded-pill">
                                                    {{ $riwayat->daftarPoli->pasien->no_rm ?? 'Belum Ada RM' }}
                                                </span>
                                            </td>

                                            <!-- No Antrian -->
                                            <td>
                                                <span class="fw-semibold text-dark">
                                                    {{ $riwayat->daftarPoli->no_antrian }}
                                                </span>
                                            </td>

                                            <!-- Nama Pasien -->
                                            <td>
                                                <div class="fw-semibold text-dark">
                                                    {{ $riwayat->daftarPoli->pasien->nama ?? '-' }}
                                                </div>
                                            </td>

                                            <!-- Keluhan -->
                                            <td class="text-muted">
                                                {{ $riwayat->daftarPoli->keluhan }}
                                            </td>

                                            <!-- Tanggal -->
                                            <td>
                                                <span class="text-muted">
                                                    {{ \Carbon\Carbon::parse($riwayat->tgl_periksa)->format('d/m/Y') }}
                                                </span>
                                            </td>

                                            <!-- Biaya -->
                                            <td>
                                                <span class="badge bg-success-subtle text-success fw-semibold px-3 py-2 rounded-pill">
                                                    Rp {{ number_format($riwayat->biaya_periksa, 0, ',', '.') }}
                                                </span>
                                            </td>

                                            <!-- Aksi -->
                                            <td class="text-center">
                                                <a href="{{ route('dokter.riwayat-pasien.show', $riwayat->id) }}"
                                                   class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </a>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="text-muted">
                                                    Belum ada riwayat pemeriksaan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End Card -->

            </div>
        </div>
    </div>
</x-layouts.app>
