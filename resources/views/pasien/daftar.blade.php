<x-layouts.app title="Poli">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-12">
                {{-- Alert flash message --}}
                @if (session('message'))
                    <div class="alert alert-{{ session('type', 'success') }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <h1 class="mb-4">Poli</h1>

                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            {{-- Form Daftar Poli --}}
                            <div class="col-4">
                                <div class="card">
                                    <h5 class="card-header bg-gray">Daftar Poli</h5>
                                    <div class="card-body">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <strong>Terjadi Kesalahan!</strong>
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <form action="{{ route('pasien.daftar.submit') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id_pasien" value="{{ $user->id }}">

                                            <div class="mb-3">
                                                <label for="no_rm" class="form-label">Nomor Rekam Medis</label>
                                                <input type="text" class="form-control" id="no_rm" name="no_rm"
                                                    value="{{ $user->no_rm }}" placeholder="Nomor Rekam Medis"
                                                    disabled>
                                            </div>

                                            <div class="mb-3">
                                                <label for="selectPoli" class="form-label">Pilih Poli</label>
                                                <select name="id_poli" id="selectPoli" class="form-control">
                                                    <option value="">-- Pilih Poli --</option>
                                                    @foreach ($polis as $poli)
                                                        <option value="{{ $poli->id }}">{{ $poli->nama_poli }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="selectJadwal" class="form-label">Pilih Jadwal
                                                    Periksa</label>
                                                <select name="id_jadwal" id="selectJadwal" class="form-control">
                                                    <option value="">-- Pilih Jadwal --</option>
                                                    @foreach ($jadwals as $jadwal)
                                                        <option value="{{ $jadwal->id }}"
                                                            data-id-poli="{{ $jadwal->dokter->poli->id ?? '' }}">
                                                            {{ $jadwal->dokter->poli->nama_poli ?? '' }} |
                                                            {{ $jadwal->hari }}, {{ $jadwal->jam_mulai }} -
                                                            {{ $jadwal->jam_selesai }} |
                                                            {{ $jadwal->dokter->nama ?? '--' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="keluhan" class="form-label">Keluhan</label>
                                                <textarea name="keluhan" id="keluhan" rows="3" class="form-control"></textarea>
                                            </div>

                                            <button type="submit" name="submit"
                                                class="btn btn-primary">Daftar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>
            </div>
        </div>
    </div>
    </div>
</x-layouts.app>

{{-- Script JS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectPoli = document.getElementById('selectPoli');
        const selectJadwal = document.getElementById('selectJadwal');

        // Saat poli dipilih, filter jadwal
        selectPoli.addEventListener('change', function() {
            const poliId = this.value;
            Array.from(selectJadwal.options).forEach(option => {
                if (option.value === "") return;
                option.hidden = !(option.dataset.idPoli == poliId && poliId !== '');
            });
            selectJadwal.value = "";
        });

        // Saat jadwal dipilih, isi poli otomatis jika belum
        selectJadwal.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const poliId = selected.dataset.idPoli;
            if (!selectPoli.value && poliId) {
                selectPoli.value = poliId;
                selectPoli.dispatchEvent(new Event('change'));
            }
        });
    });
</script>
