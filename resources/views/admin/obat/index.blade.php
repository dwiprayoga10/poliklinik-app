<x-layouts.app title="Data Obat">
    <div class="container-fluid px-4 mt-4">

        <div class="row">
            <div class="col-lg-12">

                {{-- Alert flash message --}}
                @if (session('message'))
                    <div class="alert alert-{{ session('type', 'success') }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <h1 class="mb-4">Data Obat</h1>

                <a href="{{ route('obat.create') }}" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> Tambah Obat
                </a>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle table-hover shadow-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Obat</th>
                                <th>Kemasan</th>
                                <th>Harga</th>
                                <th style="width: 220px;">Stok</th>
                                <th style="width: 180px;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($obats as $obat)
                                <tr>
                                    <td>{{ $obat->nama_obat }}</td>
                                    <td>{{ $obat->kemasan }}</td>
                                    <td>Rp{{ number_format($obat->harga, 0, ',', '.') }}</td>

                                    {{-- KOLOM STOK (AJAX TANPA REFRESH) --}}
                                    <td>
                                        <div class="stok-control" data-id="{{ $obat->id }}">

                                            {{-- Tombol minus --}}
                                            <button class="stok-btn minus"
                                                onclick="updateStok(this, 'reduce')"
                                                {{ $obat->stok == 0 ? 'disabled' : '' }}>
                                                <i class="fas fa-minus"></i>
                                            </button>

                                            {{-- Badge stok --}}
                                            <span class="stok-badge
                                                {{ $obat->stok == 0
                                                    ? 'danger'
                                                    : ($obat->stok <= 5 ? 'warning' : 'normal') }}">
                                                {{ $obat->stok }}
                                            </span>

                                            {{-- Tombol plus --}}
                                            <button class="stok-btn plus"
                                                onclick="updateStok(this, 'add')">
                                                <i class="fas fa-plus"></i>
                                            </button>

                                        </div>
                                    </td>

                                    {{-- KOLOM AKSI --}}
                                    <td class="text-center">
                                        <a href="{{ route('obat.edit', $obat->id) }}"
                                           class="btn btn-warning btn-sm me-1">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <form action="{{ route('obat.destroy', $obat->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        Tidak ada data obat
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    {{-- STYLE UI/UX --}}
    <style>
        .stok-control {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            background: #f8f9fa;
            padding: 6px 16px;
            border-radius: 999px;
            width: fit-content;
            margin: auto;
        }

        .stok-badge {
            min-width: 44px;
            text-align: center;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 14px;
        }

        .stok-badge.danger {
            background: #dc3545;
            color: #fff;
        }

        .stok-badge.warning {
            background: #ffc107;
            color: #212529;
        }

        .stok-badge.normal {
            background: #ffffff;
            color: #212529;
            border: 1px solid #dee2e6;
        }

        .stok-btn {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.15);
            transition: all 0.2s ease;
        }

        .stok-btn i {
            font-size: 12px;
        }

        .stok-btn.plus i {
            color: #198754;
        }

        .stok-btn.minus i {
            color: #dc3545;
        }

        .stok-btn:hover:not(:disabled) {
            transform: scale(1.12);
            background: #f1f1f1;
        }

        .stok-btn:disabled {
            opacity: 0.45;
            cursor: not-allowed;
        }
    </style>

    {{-- AUTO HIDE ALERT --}}
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

    {{-- AJAX UPDATE STOK --}}
    <script>
        function updateStok(button, action) {
            const container = button.closest('.stok-control');
            const id = container.dataset.id;
            const badge = container.querySelector('.stok-badge');
            const minusBtn = container.querySelector('.minus');

            fetch(`/admin/obat/${id}/stok/ajax`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status !== 'success') {
                    alert(data.message);
                    return;
                }

                const stok = data.stok;
                badge.textContent = stok;

                badge.classList.remove('danger', 'warning', 'normal');

                if (stok === 0) {
                    badge.classList.add('danger');
                    minusBtn.disabled = true;
                } else if (stok <= 5) {
                    badge.classList.add('warning');
                    minusBtn.disabled = false;
                } else {
                    badge.classList.add('normal');
                    minusBtn.disabled = false;
                }
            })
            .catch(() => alert('Terjadi kesalahan sistem'));
        }
    </script>

</x-layouts.app>
