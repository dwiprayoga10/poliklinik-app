<x-layouts.app title="Edit Obat">
    <div class="container-fluid px-4 mt-4">

        <div class="row">
            <div class="col-lg-8 offset-lg-2">

                <h1 class="mb-4">Edit Obat</h1>

                <div class="card shadow-sm">
                    <div class="card-body">

                        <form action="{{ route('obat.update', $obat->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Nama Obat + Kemasan --}}
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama_obat" class="form-label">
                                            Nama Obat <span class="text-danger">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            name="nama_obat" 
                                            id="nama_obat"
                                            class="form-control" 
                                            value="{{ old('nama_obat', $obat->nama_obat) }}" 
                                            required
                                        >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kemasan" class="form-label">
                                            Kemasan <span class="text-danger">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            name="kemasan" 
                                            id="kemasan"
                                            class="form-control" 
                                            value="{{ old('kemasan', $obat->kemasan) }}" 
                                            required
                                        >
                                    </div>
                                </div>

                            </div>

                            {{-- Stok --}}
                            <div class="mb-3">
                                <label for="stok" class="form-label">Stok Saat Ini</label>
                                <input 
                                    type="number" 
                                    id="stok"
                                    class="form-control bg-light"
                                    value="{{ $obat->stok }}"
                                    readonly
                                >
                            </div>

                            {{-- Harga --}}
                            <div class="mb-3">
                                <label for="harga" class="form-label">
                                    Harga <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    name="harga" 
                                    id="harga"
                                    class="form-control" 
                                    value="{{ old('harga', $obat->harga) }}" 
                                    required
                                    min="0"
                                    step="1"
                                >
                            </div>

                            {{-- Tombol --}}
                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="fas fa-save"></i> Update
                                </button>

                                <a href="{{ route('obat.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>
</x-layouts.app>
