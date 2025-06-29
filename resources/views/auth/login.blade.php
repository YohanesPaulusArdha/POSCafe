@extends('layouts.app')

@section('title', 'Login - Manajemen Inventaris')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="height: 100vh;">
        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <i class="fa-solid fa-store fa-3x text-primary mb-3"></i>
                        <h4>Manajemen Inventaris</h4>
                        <p class="text-muted">Silakan login untuk melanjutkan</p>
                    </div>

                    {{--error handling--}}
                    @error('email')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                           Email atau Password yang Anda masukkan salah.
                           <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @enderror


                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="contoh@email.com">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fa-regular fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Login</button>
                        </div>

                        <div class="text-center mt-4">
                            <a href="#" class="text-decoration-none">Lupa Password?</a>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3 border-0">
                    <small class="text-muted">&copy; {{ date('Y') }} Manajemen Inventaris Toko. All Rights Reserved.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const icon = togglePassword.querySelector('i');

    togglePassword.addEventListener('click', function () {
        
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        
        icon.classList.toggle('fa-eye-slash');
        icon.classList.toggle('fa-eye');
    });
});
</script>
@endpush