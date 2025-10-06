<h2>Login</h2>

@if ($errors->any())
    <p style="color: red">{{ $errors->first() }}</p>
@endif

<form action="{{ route('login') }}" method="POST">
    @csrf
    <label for="">Email:</label><br>
    <input type="email" name="email" required><br>

    <label for="">Password:</label><br>
    <input type="password" name="password" required><br>

    <button type="submit">Login</button>
</form>

<p>Belum punya akun ? <a href="{{ route('register') }}">Daftar</a></p>
