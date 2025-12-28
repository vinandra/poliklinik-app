<x-layouts.app title="Periksa Pasien">
    {{-- ALERT FLASH MESSAGE --}}
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1 class="mb-4">Periksa Pasien</h1>

                @if (session('message'))
                    <div class="alert alert-{{ session('type', 'success') }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('periksa-pasien.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_daftar_poli" value="{{ $id }}">

                            <div class="form-group mb-3">
                                <label for="obat" class="form-label">Pilih Obat</label>
                                <select id="select-obat" class="form-select">
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach ($obats as $obat)
                                        <option value="{{ $obat->id }}" data-nama="{{ $obat->nama_obat }}"
                                            data-harga="{{ $obat->harga }}" data-stok="{{ $obat->stok }}">
                                            {{ $obat->nama_obat }} - Rp{{ number_format($obat->harga) }}
                                            (Stok: {{ $obat->stok }})
                                        </option>
                                    @endforeach
                                </select>
                                <div id="alert-stok" class="mt-2"></div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea name="catatan" id="catatan" class="form-control">{{ old('catatan') }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Obat Terpilih</label>
                                <ul id="obat-terpilih" class="list-group mb-2"></ul>
                                <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="0">
                                <input type="hidden" name="obat_json" id="obat_json">
                            </div>

                            <div class="form-group mb-3">
                                <label>Total Harga</label>
                                <p id="total-harga" class="fw-bold">Rp 0</p>
                            </div>

                            <button type="submit" class="btn btn-success">Simpan</button>
                            <a href="{{ route('periksa-pasien.index') }}" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

<script>
    const selectObat = document.getElementById('select-obat');
    const listObat = document.getElementById('obat-terpilih');
    const inputBiaya = document.getElementById('biaya_periksa');
    const inputObatJson = document.getElementById('obat_json');
    const totalHargaEl = document.getElementById('total-harga');
    const alertStok = document.getElementById('alert-stok');

    let daftarObat = [];

    selectObat.addEventListener('change', () => {
        const selectedOption = selectObat.options[selectObat.selectedIndex];
        const id = selectedOption.value;
        const nama = selectedOption.dataset.nama;
        const harga = parseInt(selectedOption.dataset.harga || 0);
        const stok = parseInt(selectedOption.dataset.stok || 0);

        if (!id || daftarObat.some(o => o.id == id)) {
            return;
        }

        tampilkanPeringatanStok(stok, nama);

        daftarObat.push({
            id,
            nama,
            harga
        });
        renderObat();
        selectObat.selectedIndex = 0;
    });

    function renderObat() {
        listObat.innerHTML = '';
        let total = 0;

        daftarObat.forEach((obat, index) => {
            total += obat.harga;

            const item = document.createElement('li');
            item.className = 'list-group-item d-flex justify-content-between align-items-center';
            item.innerHTML = `
                ${obat.nama} - Rp ${obat.harga.toLocaleString()}
                <button type="button" class="btn btn-sm btn-danger" onclick="hapusObat(${index})">Hapus</button>
            `;
            listObat.appendChild(item);
        });

        inputBiaya.value = total;
        totalHargaEl.textContent = `Rp ${total.toLocaleString()}`;
        inputObatJson.value = JSON.stringify(daftarObat.map(o => o.id));
    }

    function hapusObat(index) {
        daftarObat.splice(index, 1);
        renderObat();
    }

    function tampilkanPeringatanStok(stok, nama) {
        alertStok.innerHTML = '';

        if (stok === 0) {
            alertStok.innerHTML = `
                <div class="alert alert-danger py-2 mb-0" role="alert">
                    Stok ${nama} habis. Mohon pastikan ketersediaan sebelum diberikan.
                </div>
            `;
            return;
        }

        if (stok < 5) {
            alertStok.innerHTML = `
                <div class="alert alert-warning py-2 mb-0" role="alert">
                    Stok ${nama} menipis (${stok} tersisa).
                </div>
            `;
        }
    }
</script>
