<form id="formUpdRoles">
    <div class="form-group">
        <label for="name">Nama Roles</label>
        <input type="text" class="form-control" name="valName" placeholder="Masukkan nama roles" autocomplete="off"
            value="{{ $dataRole->name }}">
    </div>

    @if ($dataPermissions->isNotEmpty())
        @foreach ($dataPermissions as $permissions)
            <div class="form-group">
                <label class="custom-switch form-switch">
                    <input type="checkbox" class="custom-switch-input"
                        id="permission_{{ $permissions->code_permissions }}" name="selectedPermissions[]"
                        value="{{ $permissions->name }}"
                        {{ $hasPermissions->contains($permissions->name) ? 'checked' : '' }}>
                    <span class="custom-switch-indicator"></span>
                    <span class="custom-switch-description">{{ $permissions->name }}</span>
                </label>
            </div>
        @endforeach
    @endif

    <div class="form-group">
        <button type="submit" id="SubmitBtnUpdates" class="btn btn-sm btn-block btn-primary">Ubah Data</button>
        <div id="loadingSpinnUpdates" class="d-none btn btn-sm btn-block btn-danger">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Proses Ubah...
        </div>
    </div>
</form>

<script>
    document.getElementById('formUpdRoles').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData(form);

        document.getElementById('SubmitBtnUpdates').classList.add('d-none');
        document.getElementById('loadingSpinnUpdates').classList.remove('d-none');
        for (var pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        formData.append('_method', 'PUT');
        axios.post('{{ route('roles.update', ['roleCode' => $dataRole->code_role]) }}', formData, {
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
                    errorMessages = 'Terjadi kesalahan saat mengupdate data.';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: errorMessages
                });

                document.getElementById('SubmitBtnUpdates').classList.remove('d-none');
                document.getElementById('loadingSpinnUpdates').classList.add('d-none');
            });
    });
</script>
