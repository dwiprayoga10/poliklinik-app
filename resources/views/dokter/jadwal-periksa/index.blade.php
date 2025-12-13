<x-layouts.app title="Jadwal Periksa">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-12">

                {{-- Alert flash message --}}
                @if (session('message'))
                    <div class="alert alert-{{ session('type', 'success') }} alert-dismissible fade show shadow-sm rounded-3"
                         role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <span>{{ session('message') }}</span>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="fw-bold mb-1">Jadwal Periksa</h1>
                        <p class="text-muted mb-0">
                            Kelola jadwal praktik dokter secara terstruktur
                        </p>
                    </div>

                    <a href="{{ route('jadwal-periksa.create') }}"
                       class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-plus me-1"></i> Tambah Jadwal
                    </a>
                </div>

                <!-- Card Table -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th>ID</th>
                                        <th>Hari</th>
                                        <th>Jam Mulai</th>
                                        <th>Jam Selesai</th>
                                        <th class="text-center" style="width: 160px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($jadwalPeriksas as $index => $jadwalPeriksa)

                                        <tr>
                                            
                                            <td class="fw-semibold text-muted">
    {{ $index + 1 }}
</td>


                                            <td>
                                                <span class="fw-semibold text-dark">
                                                    {{ $jadwalPeriksa->hari }}
                                                </span>
                                            </td>
                                            

                                            <td>
                                                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                                                    {{ \Carbon\Carbon::parse($jadwalPeriksa->jam_mulai)->format('H:i') }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill">
                                                    {{ \Carbon\Carbon::parse($jadwalPeriksa->jam_selesai)->format('H:i') }}
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                <a href="{{ route('jadwal-periksa.edit', $jadwalPeriksa->id) }}"
                                                   class="btn btn-outline-warning btn-sm rounded-pill px-3 me-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('jadwal-periksa.destroy', $jadwalPeriksa->id) }}"
                                                      method="POST"
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger btn-sm rounded-pill px-3"
                                                        onclick="return confirm('Yakin ingin menghapus Data Jadwal Periksa ini ?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-muted">
                                                    Belum ada Jadwal Periksa
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

    <script>
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }
        }, 2000);
    </script>
</x-layouts.app>
