{{-- <h2>Login</h2> --}}
{{--
@if ($errors->any())
    <p style="color:red">{{ $errors->first() }}</p>
@endif --}}

{{-- <form method="POST" action="{{ route('login') }}">
    @csrf
    <label>Email:</label><br>
    <input type="email" name="email" required><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form> --}}

<x-layouts.guest title="Login">
    <div class="login-box d-flex flex-column justify-content-center align-items-center w-100 vh-100">
        <div class="card card-outline card-primary">
            <div class="card-header text-center text-lg">
                <b>Poli</b>klinik
            </div>
            <div class="card-body">
                <p class="login-box-msg">Login ke akun anda</p>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Email" name="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <p class="alert alert-danger">{{ $errors->first() }}</p>
                    @endif

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block" name="submit">Login</button>
                        </div>
                    </div>
                </form>

                <div class="row mt-3 justify-content-center">
                    <div class="col-12 text-center">
                        <span>Belum punya akun? <a href="{{ route('register') }}">Register</a></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>
