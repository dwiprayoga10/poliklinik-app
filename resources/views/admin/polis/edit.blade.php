<x-layouts.app>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header Section -->
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-primary mb-2">Edit Data Poli</h2>
                    <p class="text-muted">Perbarui informasi poli dengan data terbaru</p>
                    <hr class="w-25 mx-auto border-primary opacity-75">
                </div>

                <!-- Card Form -->
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <form action="{{ route('polis.update', $poli->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Nama Poli -->
                            <div class="mb-4">
                                <label for="nama_poli" class="form-label fw-semibold">Nama Poli <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    id="nama_poli" 
                                    name="nama_poli"
                                    class="form-control form-control-lg rounded-3 shadow-sm @error('nama_poli') is-invalid @enderror"
                                    placeholder="Masukkan nama poli..."
                                    value="{{ old('nama_poli', $poli->nama_poli) }}"
                                    required
                                >
                                @error('nama_poli')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Keterangan -->
                            <div class="mb-4">
                                <label for="keterangan" class="form-label fw-semibold">Keterangan</label>
                                <textarea 
                                    id="keterangan" 
                                    name="keterangan"
                                    class="form-control form-control-lg rounded-3 shadow-sm @error('keterangan') is-invalid @enderror"
                                    placeholder="Tuliskan keterangan singkat..."
                                    rows="4">{{ old('keterangan', $poli->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('polis.index') }}" class="btn btn-outline-secondary btn-lg rounded-3 px-4">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg rounded-3 px-5 shadow-sm">
                                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Small Footer Text -->
                <div class="text-center mt-4 text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    Pastikan semua data yang diubah sudah benar sebelum menyimpan.
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Smooth fade-in animation */
        .card {
            animation: fadeIn 0.6s ease-in-out;
            background: #ffffff;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            min-height: 100vh;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-control:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 0.25rem rgba(74, 144, 226, 0.25);
        }

        h2 {
            letter-spacing: 0.5px;
        }
    </style>
</x-layouts.app>
