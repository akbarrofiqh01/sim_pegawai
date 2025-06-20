@php
    $jnsCuti = [
        'tahunan' => 'Tahunan',
        'izin' => 'Izin',
        'sakit' => 'Sakit',
        'melahirkan' => 'Melahirkan',
        'penting' => 'Penting',
    ];
@endphp
<form id="form_newCuti">
    <div class="form-group">
        <label for="">Jenis Cuti</label>
        <select class="form-control" name="jnsCuti">
            <option disabled selected>Pilih jenis</option>
            @foreach ($jnsCuti as $keyCuti => $valCuti)
                <option value="{{ $keyCuti }}">{{ $valCuti }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="">Tanggal Mulai Cuti</label>
        <input type="date" class="form-control" name="tglMulai" autocomplete="off">
    </div>
    <div class="form-group">
        <label for="">Tanggal Akhir Cuti</label>
        <input type="date" class="form-control" name="tglAkhir" autocomplete="off">
    </div>
    <div class="form-group">
        <label for="">Alasan Cuti</label>
        <textarea class="form-control" name="alasanCuti" rows="5" style="resize: none"
            placeholder="Masukkan alasan cuti anda" autocomplete="off"></textarea>
    </div>
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
    document.getElementById('form_newCuti').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData(form);

        document.getElementById('SubmitBtn').classList.add('d-none');
        document.getElementById('loadingSpinner').classList.remove('d-none');

        axios.post('{{ route('cuti.store') }}', formData, {
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
