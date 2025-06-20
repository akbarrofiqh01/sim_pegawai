<form id="formUbahPermissions">
    <div class="form-group">
        <label for="">Nama Permissions</label>
        <input type="text" class="form-control" name="name" placeholder="Masukkan nama permissions" autocomplete="off"
            value="{{ $dataPermissions->name }}">
    </div>
    <div class="form-group">
        <button type="submit" id="SubmitUpdBtn" class="btn btn-block btn-sm btn-primary btn-space mb-0">Ubah
            Data</button>
        <div id="loadingUpdSpinner" class="d-none btn btn-block btn-sm btn-danger btn-space mb-0">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Proses Ubah...
        </div>
    </div>
</form>
<script>
    document.getElementById('formUbahPermissions').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData(form);

        document.getElementById('SubmitUpdBtn').classList.add('d-none');
        document.getElementById('loadingUpdSpinner').classList.remove('d-none');

        formData.append('_method', 'PUT');
        axios.post(
                '{{ route('permissions.update', ['permissionscode' => $dataPermissions->code_permissions]) }}',
                formData, { // Mengirim formData sebagai data
                    headers: {
                        'X-CSRF-TOKEN': token,
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
                    errorMessages = 'Terjadi kesalahan saat mengubah data.';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: errorMessages
                });

                document.getElementById('SubmitUpdBtn').classList.remove('d-none');
                document.getElementById('loadingUpdSpinner').classList.add('d-none');
            });
    });
</script>
