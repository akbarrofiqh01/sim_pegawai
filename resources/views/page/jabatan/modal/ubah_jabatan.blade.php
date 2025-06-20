<form id="formUpdJabatan">
    <div class="form-group">
        <label for="">Nama Jabatan</label>
        <input type="text" class="form-control" name="nameJabatan" placeholder="Masukkan nama jabatan" autocomplete="off"
            value="{{ $datajabatan->nama_jabatan }}" </div>
        <div class="form-group">
            <label for="">Gaji Pokok</label>
            <input type="text" class="form-control" name="gajiPokok" id="gajiPokok2"
                placeholder="Masukkan gaji pokok" autocomplete="off" value="{{ $datajabatan->gaji_pokok }}">
        </div>
        <div class="form-group">
            <button type="submit" id="SubmitBtn2" class="btn btn-sm btn-block btn-primary btn-space mb-0">Ubah
                Data</button>
            <div id="loadingSpinner2" class="d-none btn btn-sm btn-block btn-danger btn-space mb-0">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Proses Ubah...
            </div>
        </div>
</form>
<script>
    new AutoNumeric('#gajiPokok2', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        decimalPlaces: 0,
        unformatOnSubmit: true
    });

    document.getElementById('formUpdJabatan').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData(form);

        document.getElementById('SubmitBtn2').classList.add('d-none');
        document.getElementById('loadingSpinner2').classList.remove('d-none');

        formData.append('_method', 'PUT');
        axios.post('{{ route('jabatan.update', ['jabatanCode' => $datajabatan->code_jabatan]) }}', formData, {
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
                    errorMessages = 'Terjadi kesalahan saat menghapus data.';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: errorMessages
                });

                document.getElementById('SubmitBtn2').classList.remove('d-none');
                document.getElementById('loadingSpinner2').classList.add('d-none');
            });
    });
</script>
