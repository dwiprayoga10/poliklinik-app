<x-layouts.app title="Periksa Pasien">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1 class="mb-4 fw-bold">Periksa Pasien</h1>

                {{-- ALERT ERROR LARAVEL --}}
                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm rounded">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card shadow-sm rounded-4 border-0">
                    <div class="card-body">

                        <form action="{{ route('periksa-pasien.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_daftar_poli" value="{{ $id }}">

                            {{-- PILIH OBAT --}}
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold">Pilih Obat</label>
                                <select id="select-obat" class="form-select rounded-3">
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach ($obats as $obat)
                                        <option value="{{ $obat->id }}"
                                            data-nama="{{ $obat->nama_obat }}"
                                            data-harga="{{ $obat->harga }}"
                                            data-stok="{{ $obat->stok }}">
                                            {{ $obat->nama_obat }}
                                            (stok: {{ $obat->stok }})
                                            - Rp{{ number_format($obat->harga) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- CATATAN --}}
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold">Catatan</label>
                                <textarea name="catatan"
                                          class="form-control rounded-3"
                                          rows="3">{{ old('catatan') }}</textarea>
                            </div>

                            {{-- OBAT TERPILIH --}}
                            <div class="form-group mb-3">
                                <label class="fw-semibold">Obat Terpilih</label>
                                <ul id="obat-terpilih" class="list-group mb-2"></ul>

                                <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="0">
                                <input type="hidden" name="obat_json" id="obat_json">
                            </div>

                            {{-- TOTAL --}}
                            <div class="form-group mb-4">
                                <label class="fw-semibold">Total Harga Obat</label>
                                <p id="total-harga" class="fw-bold fs-5 text-success">Rp 0</p>
                            </div>

                            {{-- BUTTON --}}
                            <button type="submit" class="btn btn-success px-4 rounded-3">
                                Simpan
                            </button>
                            <a href="{{ route('periksa-pasien.index') }}"
                               class="btn btn-secondary px-4 rounded-3">
                                Kembali
                            </a>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

{{-- SWEETALERT2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- SCRIPT --}}
<script>
    const selectObat = document.getElementById('select-obat');
    const listObat = document.getElementById('obat-terpilih');
    const inputBiaya = document.getElementById('biaya_periksa');
    const inputObatJson = document.getElementById('obat_json');
    const totalHargaEl = document.getElementById('total-harga');

    let daftarObat = [];

    selectObat.addEventListener('change', () => {
        const opt = selectObat.options[selectObat.selectedIndex];

        const id    = opt.value;
        const nama  = opt.dataset.nama;
        const harga = parseInt(opt.dataset.harga || 0);
        const stok  = parseInt(opt.dataset.stok || 0);

        if (!id || daftarObat.some(o => o.id == id)) {
            selectObat.selectedIndex = 0;
            return;
        }

        // ❌ STOK HABIS
        if (stok === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Stok Habis',
                text: `Obat "${nama}" sudah habis.`,
                confirmButtonColor: '#dc3545'
            });
            selectObat.selectedIndex = 0;
            return;
        }
        

        // ⚠️ STOK MENIPIS
        if (stok <= 5) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Menipis',
                html: `Obat <b>${nama}</b> tersisa <b>${stok}</b> unit.`,
                timer: 2500,
                showConfirmButton: false
            });
        }

        daftarObat.push({ id, nama, harga });
        renderObat();
        selectObat.selectedIndex = 0;
    });

    function renderObat() {
        listObat.innerHTML = '';
        let total = 0;

        daftarObat.forEach((obat, index) => {
            total += obat.harga;

            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center rounded-3 shadow-sm mb-2';
            li.innerHTML = `
                <span>
                    <strong>${obat.nama}</strong>
                    <span class="text-muted"> - Rp ${obat.harga.toLocaleString()}</span>
                </span>
                <button type="button"
                        class="btn btn-outline-danger btn-sm"
                        onclick="hapusObat(${index})">
                    Hapus
                </button>
            `;
            listObat.appendChild(li);
        });

        inputBiaya.value = total;
        inputObatJson.value = JSON.stringify(daftarObat.map(o => o.id));
        totalHargaEl.textContent = 'Rp ' + total.toLocaleString();
    }

    function hapusObat(index) {
        Swal.fire({
            title: 'Hapus Obat?',
            text: 'Obat akan dihapus dari daftar.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                daftarObat.splice(index, 1);
                renderObat();

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Obat berhasil dihapus.',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }
</script>
