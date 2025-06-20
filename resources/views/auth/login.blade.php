@extends('layouts.auth')
@section('title', 'Login')

@section('content')
    <form class="login100-form validate-form" id="loginForm">
        @csrf
        <span class="login100-form-title pb-5">
            SIM Login
        </span>
        <div class="panel panel-primary">
            <div class="panel-body tabs-menu-body p-0 pt-5">
                <div class="wrap-input100 validate-input input-group" data-bs-validate="Valid email is required: ex@abc.xyz">
                    <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                        <i class="zmdi zmdi-email text-muted" aria-hidden="true"></i>
                    </a>
                    <input class="input100 border-start-0 form-control ms-0" type="text" placeholder="Email"
                        name="email" id="auth_email" autocomplete="off">
                </div>
                <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                    <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                        <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                    </a>
                    <input class="input100 border-start-0 form-control ms-0" type="password" placeholder="Password"
                        autocomplete="off" name="password" id="auth_password">
                </div>
                <div class="container-login100-form-btn">
                    <button type="submit" id="loginBtn" class="login100-form-btn btn-primary">Login</button>
                    <div id="loadingSpinner" class="d-none login100-form-btn btn-danger">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Proses Login...
                    </div>
                </div>

            </div>
        </div>
    </form>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            document.getElementById('loginBtn').classList.add('d-none');
            document.getElementById('loadingSpinner').classList.remove('d-none');

            axios.post('{{ route('login') }}', formData, {
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Login',
                        text: response.data.message || 'Login berhasil!',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = response.data.redirect || '/dashboard';
                    });
                })
                .catch(function(error) {
                    let errorMessages = '';

                    if (error.response && error.response.data && error.response.data.csrf_token) {
                        axios.defaults.headers.common['X-CSRF-TOKEN'] = error.response.data.csrf_token;
                        const meta = document.querySelector('meta[name="csrf-token"]');
                        if (meta) {
                            meta.setAttribute('content', error.response.data.csrf_token);
                        }
                    }

                    if (error.response && error.response.status === 422 && error.response.data.errors) {
                        Object.values(error.response.data.errors).forEach(function(messages) {
                            messages.forEach(function(message) {
                                errorMessages += `${message}<br>`;
                            });
                        });
                    } else if (error.response && error.response.data.message) {
                        errorMessages = `${error.response.data.message}<br>`;
                    } else {
                        errorMessages = 'Terjadi kesalahan saat login.';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Login Gagal',
                        html: errorMessages
                    });

                    document.getElementById('loginBtn').classList.remove('d-none');
                    document.getElementById('loadingSpinner').classList.add('d-none');
                });
        });
    </script>
@endsection
