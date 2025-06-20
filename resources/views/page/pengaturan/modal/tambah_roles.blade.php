<form id="formNewRoles">
    <div class="form-group">
        <label for="">Nama Roles</label>
        <input type="text" class="form-control" name="name" placeholder="Masukkan nama roles" autocomplete="off">
    </div>
    @if ($getPermissions->isNotEmpty())
        @foreach ($getPermissions as $permissions)
            <div class="form-group">
                <label class="custom-switch form-switch">
                    <input type="checkbox" class="custom-switch-input"
                        id="permission_{{ $permissions->code_permissions }}" name="permissions[]"
                        value="{{ $permissions->name }}"> {{-- atau $permissions->name jika pakai name --}}
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description">{{ $permissions->name }}</span>
                </label>
            </div>
        @endforeach
    @endif
    <div class="form-group">
        <button type="submit" id="SubmitBtn" class="btn btn-sm btn-block btn-primary btn-space mb-0">Tambah
            Data</button>
        <div id="loadingSpinner" class="d-none btn btn-sm btn-block btn-danger btn-space mb-0">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Proses Tambah...
        </div>
    </div>
</form>
<script>
    document.getElementById('formNewRoles').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData(form);

        document.getElementById('SubmitBtn').classList.add('d-none');
        document.getElementById('loadingSpinner').classList.remove('d-none');

        axios.post('{{ route('roles.store') }}', formData, {
                headers: {
                    'X-CSRF-TOKEN': token
                }
            })
            .then(function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
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
                    errorMessages = 'Terjadi kesalahan saat menambah data.';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: errorMessages
                });

                document.getElementById('SubmitBtn').classList.remove('d-none');
                document.getElementById('loadingSpinner').classList.add('d-none');
            });
    });
</script>
