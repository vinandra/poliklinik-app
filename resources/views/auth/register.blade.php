<h2>Register</h2>

@if ($errors->any())
    <ul style="color: red">
        {{-- Perbaikan sintaks di bawah ini --}}
        @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
@endif

<form action="{{ route('register') }}" method="POST">
    @csrf
    <label for="">Nama Lengkap:</label>
    <input type="text" name="nama" id="" required><br>

    <label for="">Email:</label>
    <input type="email" name="email" id="" required><br>

    <label for="">Alamat:</label>
    <input type="text" name="alamat" id="" required><br>

    <label for="">No HP:</label>
    <input type="text" name="no_hp" id="" required><br>

    <label for="">No KTP:</label>
    <input type="text" name="no_ktp" id="" required><br>

    {{-- BARIS INI DITAMBAHKAN/DIPERBAIKI UNTUK PASSWORD UTAMA --}}
    <label for="">Password:</label>
    <input type="password" name="password" id="" required><br>

    {{-- Baris ini diperbaiki labelnya menjadi Konfirmasi Password --}}
    <label for="">Konfirmasi Password:</label>
    <input type="password" name="password_confirmation" id="" required><br>

    <button type="submit">Daftar</button>

</form>

<p>Sudah punya akun ? <a href="{{ route('login') }}">Login</a></p>
