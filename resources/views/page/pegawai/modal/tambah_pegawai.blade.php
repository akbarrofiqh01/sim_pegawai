@php
    $aryStNikah = [
        'TK' => 'Tidak Kawin / Belum Menikah',
        'K0' => 'Kawin, tanpa tanggungan',
        'K1' => 'Kawin, dengan 1 tanggungan',
        'K2' => 'Kawin, dengan 2 tanggungan',
        'K3' => 'Kawin, dengan ≥3 tanggungan',
        'J' => 'Janda',
        'D' => 'Duda',
    ];

    $jnsK = [
        'L' => 'Laki-Laki',
        'P' => 'Perempuan',
    ];
@endphp
<form id="formNwPegawai">
    <div class="form-group">
        <label for="">NIP</label>
        <input type="text" class="form-control" name="nipppp" placeholder="Masukkan NIP" autocomplete="off">
    </div>
    <div class="form-group">
        <label for="">Jabatan</label>
        <select class="form-control" name="jbtan">
            <option disabled selected>Pilih jabatan</option>
            @foreach ($jabatan as $dtaJabatan)
                <option value="{{ $dtaJabatan->code_jabatan }}">{{ $dtaJabatan->nama_jabatan }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="">Nama Lengkap</label>
        <input type="text" class="form-control" name="fullname" placeholder="Masukkan nama lengkap"
            autocomplete="off">
    </div>
    <div class="form-group">
        <label for="">Nomor HP / Whatsapp</label>
        <input type="text" class="form-control" name="noTelp" placeholder="Masukkan nomor HP / whatsapp"
            autocomplete="off">
    </div>
    <div class="row">
        <!-- Kolom Email -->
        <div class="col-sm-12 col-md-6 mb-3">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email"
                    autocomplete="off">
            </div>
        </div>

        <!-- Kolom Konfirmasi Password -->
        <div class="col-sm-12 col-md-6 mb-3">
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group" id="show_hide_password">
                    <input type="password" name="password" id="password" class="form-control"
                        placeholder="Konfirmasi Password" autocomplete="off">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fa fa-eye-slash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="">Status Nikah</label>
                <select class="form-control" name="stNikah">
                    <option disabled selected>Pilih status</option>
                    @foreach ($aryStNikah as $val => $stNikah)
                        <option value="{{ $val }}">{{ $stNikah }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-12 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="">Jenis Kelamin</label>
                <select class="form-control" name="jnsk">
                    <option disabled selected>Pilih status</option>
                    @foreach ($jnsK as $vals => $jk)
                        <option value="{{ $vals }}">{{ $jk }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-12 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="">Tempat Lahir</label>
                <input type="text" class="form-control" name="tmpLahir" placeholder="Masukkan tempat Lahir"
                    autocomplete="off">
            </div>
        </div>
        <div class="col-sm-12 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="">Tanggal Lahir</label>
                <input type="date" class="form-control" name="tglLahir" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="">Agama</label>
        <input type="text" class="form-control" name="agamages" placeholder="Masukkan agama" autocomplete="off">
    </div>
    <div class="form-group">
        <label for="">Alamat</label>
        <textarea type="text" class="form-control" name="almt" placeholder="Masukkan Alamat" style="resize: none;"
            autocomplete="off"></textarea>
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
    $(document).ready(function() {
        $("#show_hide_password button").on('click', function(event) {
            event.preventDefault();
            let input = $('#show_hide_password input');
            let icon = $('#show_hide_password i');

            if (input.attr("type") === "text") {
                input.attr('type', 'password');
                icon.addClass("fa-eye-slash").removeClass("fa-eye");
            } else {
                input.attr('type', 'text');
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });
    });

    document.getElementById('formNwPegawai').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData(form);

        document.getElementById('SubmitBtn').classList.add('d-none');
        document.getElementById('loadingSpinner').classList.remove('d-none');

        axios.post('{{ route('pegawai.store') }}', formData, {
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
