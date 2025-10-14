<x-layouts.app>
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1 class="mb-4">Tambah Poli</h1>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('polis.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="nama_poli" class="form-label">Nama Poli <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('nama_poli') is-invalid @enderror" id="nama_poli"
                                            name="nama_poli" value="{{ old('nama_poli') }}" required>
                                        @error('nama_poli')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="keterangan" class="form-label">Keterangan<span
                                                class="text-danger">*</span></label>
                                        <textarea required name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                                        @error('keterangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="{{ route('polis.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
