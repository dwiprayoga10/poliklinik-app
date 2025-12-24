<x-layouts.app title="Data Obat">
    <div class="container-fluid px-4 mt-4">

        {{-- FLASH MESSAGE (SUKSES / ERROR) --}}
        @if (session('message'))
            <div class="alert alert-{{ session('type', 'success') }}">
                {{ session('message') }}
            </div>
        @endif

        {{-- ========================= --}}
        {{-- PERINGATAN STOK OBAT --}}
        {{-- ========================= --}}
        @php
            // Semua obat dengan stok <= 5 (termasuk 0)
            $stokMenipis = $obats->filter(fn($obat) => $obat->stok <= 5);

            $stokHabis  = $stokMenipis->where('stok', 0)->count();
            $stokKritis = $stokMenipis->whereBetween('stok', [1, 5])->count();
        @endphp

        @if ($stokMenipis->count() > 0)
            <div class="alert alert-warning d-flex align-items-center shadow-sm">
                <i class="fas fa-exclamation-triangle fs-4 me-3"></i>
                <div>
                    <strong>Peringatan Stok Obat!</strong><br>

                    @if ($stokHabis > 0)
                        <span class="text-danger fw-bold">
                            {{ $stokHabis }} obat telah habis stok.
                        </span><br>
                    @endif

                    @if ($stokKritis > 0)
 <span>
    Terdapat {{ $stokKritis }} obat dengan persediaan hampir habis (≤ 5).
</span><br>

                    @endif

                    <small class="text-muted">
                        Segera lakukan penambahan stok untuk menghindari gangguan pelayanan.
                    </small>
                </div>
            </div>
        @endif

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">Data Obat</h1>
            <a href="{{ route('obat.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Obat
            </a>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th>Nama Obat</th>
                        <th>Kemasan</th>
                        <th>Harga</th>
                        <th class="text-center" style="width: 140px;">Stok</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($obats as $obat)
                        <tr>
                            <td>{{ $obat->nama_obat }}</td>
                            <td>{{ $obat->kemasan }}</td>
                            <td>Rp{{ number_format($obat->harga, 0, ',', '.') }}</td>

                            {{-- STOK --}}
                            <td class="text-center">
                                <span class="badge fs-6 px-3 py-2
                                    {{ $obat->stok == 0
                                        ? 'bg-danger'
                                        : ($obat->stok <= 5 ? 'bg-warning text-dark' : 'bg-success') }}">
                                    {{ $obat->stok }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown">
                                        Aksi
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#stokModal{{ $obat->id }}">
                                                <i class="fas fa-box me-2 text-info"></i>
                                                Kelola Stok
                                            </button>
                                        </li>

                                        <li>
                                            <a class="dropdown-item"
                                               href="{{ route('obat.edit', $obat->id) }}">
                                                <i class="fas fa-edit me-2 text-warning"></i>
                                                Edit
                                            </a>
                                        </li>

                                        <li><hr class="dropdown-divider"></li>

                                        <li>
                                            <form action="{{ route('obat.destroy', $obat->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus data obat ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger">
                                                    <i class="fas fa-trash me-2"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL KELOLA STOK --}}
                        <div class="modal fade" id="stokModal{{ $obat->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('obat.stok.update', $obat->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Kelola Stok Obat</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <p><strong>{{ $obat->nama_obat }}</strong></p>
                                            <p class="text-muted">
                                                Stok saat ini: {{ $obat->stok }} unit
                                            </p>

                                            <div class="mb-3">
                                                <label class="form-label">Aksi</label>
                                                <select name="tipe" class="form-select" required>
                                                    <option value="tambah">Tambah Stok</option>
                                                    <option value="kurang">Kurangi Stok</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Jumlah</label>
                                                <input type="number"
                                                       name="jumlah"
                                                       class="form-control"
                                                       min="1"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal">
                                                Batal
                                            </button>
                                            <button class="btn btn-primary">
                                                Simpan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Tidak ada data obat
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- HILANGKAN GARIS BAWAH LINK DI ALERT --}}
    <style>
        .alert a {
            text-decoration: none !important;
        }
    </style>
</x-layouts.app>
