@extends('layouts.backend')
@section('title', 'Pegawai - SIM Pegawai')
@section('title-content', 'Pegawai')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ __('Data Pegawai') }}
            @can('tombol tambah pegawai')
                <div class="card-options">
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#dinamicModals" data-title="Tambah Pegawai"
                        data-href="{{ route('pegawai.create') }}" class="btn btn-primary btn-sm" title="tambah roles">
                        <i class="fe fe-plus-circle"></i> Tambah
                    </a>
                </div>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example3" class="table table-bordered text-nowrap border-bottom">
                    <thead>
                        <tr>
                            <th class="border-bottom-0" width="5%">No</th>
                            <th class="border-bottom-0">Jabatan</th>
                            <th class="border-bottom-0">NIP</th>
                            <th class="border-bottom-0">Nama</th>
                            <th class="border-bottom-0">Email</th>
                            <th class="border-bottom-0">Status Nikah</th>
                            <th class="border-bottom-0">Jenis Kelamin</th>
                            <th class="border-bottom-0">Tempat/Tgl Lahir</th>
                            <th class="border-bottom-0">Agama</th>
                            <th class="border-bottom-0">No Telp</th>
                            <th class="border-bottom-0">Alamat</th>
                            <th class="border-bottom-0">Roles</th>
                            <th class="border-bottom-0">Dibuat</th>
                            <th class="border-bottom-0" width="25%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pegawai as $dataPegawai)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucfirst($dataPegawai->jabatan->nama_jabatan) }}</td>
                                <td>{{ $dataPegawai->nip }}</td>
                                <td>{{ ucfirst($dataPegawai->fullname) }}</td>
                                <td>{{ $dataPegawai->email }}</td>
                                <td>
                                    @if ($dataPegawai->status_nikah == 'TK')
                                        Tidak Kawin / Belum Menikah
                                    @elseif($dataPegawai->status_nikah == 'K0')
                                        Kawin, tanpa tanggungan
                                    @elseif($dataPegawai->status_nikah == 'K1')
                                        Kawin, dengan 1 tanggungan
                                    @elseif($dataPegawai->status_nikah == 'K2')
                                        Kawin, dengan 2 tanggungan
                                    @elseif($dataPegawai->status_nikah == 'K3')
                                        Kawin, dengan ≥3 tanggungan
                                    @elseif($dataPegawai->status_nikah == 'D')
                                        Duda
                                    @elseif($dataPegawai->status_nikah == 'J')
                                        Janda
                                    @endif
                                </td>
                                <td>
                                    @if ($dataPegawai->jenis_kelamin == 'P')
                                        Perempuan
                                    @else
                                        Laki-Laki
                                    @endif
                                </td>
                                <td>{{ ucfirst($dataPegawai->tempat_lahir) . ', ' . \Carbon\Carbon::parse($dataPegawai->tanggal_lahir)->format('d M, Y') }}
                                <td>{{ ucfirst($dataPegawai->agama) }}</td>
                                <td>{{ ucfirst($dataPegawai->no_telp) }}</td>
                                <td>{{ ucfirst($dataPegawai->alamat) }}</td>
                                <td>
                                    @if ($dataPegawai->user && $dataPegawai->user->roles && $dataPegawai->user->roles->count())
                                        {{ Str::ucfirst($dataPegawai->user->roles->pluck('name')->join(', ')) }}
                                    @else
                                        <span class="text-muted">Belum ada role</span>
                                    @endif
                                </td>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($dataPegawai->created)->format('d M, Y') }}</td>
                                <td>
                                    @can('tombol ubah pegawai')
                                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#dinamicModals"
                                            data-title="Ubah Pegawai"
                                            data-href="{{ route('pegawai.edit', ['codePegawai' => $dataPegawai->code_pegawai]) }}"
                                            class="btn btn-primary btn-sm" title="ubah pegawai">
                                            <i class="fe fe-edit"></i>
                                        </a>
                                    @endcan
                                    @can('tombol hapus pegawai')
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger text-white "
                                            onclick="hapusConfirm('{{ $dataPegawai->code_pegawai }}')" title="hapus pegawai">
                                            <i class="fe fe-trash-2"></i>
                                        </a>
                                    @endcan
                                    @can('tombol roles pegawai')
                                        <a data-href="{{ route('pegawai.roles', ['codePegawai' => $dataPegawai->code_pegawai]) }}"
                                            data-bs-title="Roles Pegawai" data-bs-remote="false" data-bs-toggle="modal"
                                            data-bs-target="#dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false"
                                            class="btn btn-sm btn-primary text-white" title="roles pegawai">
                                            <i class="mdi mdi-account-settings"></i>
                                        </a>
                                    @endcan
                                    @can('tombol login pegawai')
                                        <a href="javascript:void(0)" class="btn btn-sm btn-primary text-white"
                                            title="Dashboard {{ $dataPegawai->user->name }}"
                                            onclick="loginSebagaiUser('{{ $dataPegawai->user->code_user }}')"><i
                                                class="fe fe-log-in"></i></a>
                                    @endcan

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="border-bottom-0" width="5%">No</th>
                            <th class="border-bottom-0">Jabatan</th>
                            <th class="border-bottom-0">NIP</th>
                            <th class="border-bottom-0">Nama</th>
                            <th class="border-bottom-0">Email</th>
                            <th class="border-bottom-0">Status Nikah</th>
                            <th class="border-bottom-0">Jenis Kelamin</th>
                            <th class="border-bottom-0">Tempat/Tgl Lahir</th>
                            <th class="border-bottom-0">Agama</th>
                            <th class="border-bottom-0">No Telp</th>
                            <th class="border-bottom-0">Alamat</th>
                            <th class="border-bottom-0">Roles</th>
                            <th class="border-bottom-0">Dibuat</th>
                            <th class="border-bottom-0" width="25%">Aksi</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#example3').DataTable({
                responsive: true
            });
        });

        function loginSebagaiUser(usercode) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Ingin login sebagai user ini !',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Login!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    axios.post(`/impersonate/${usercode}`, {}, {
                            headers: {
                                'X-CSRF-TOKEN': token
                            }
                        })
                        .then(res => {
                            Swal.fire({
                                title: 'Berhasil',
                                text: res.data.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = res.data.redirect;
                            });
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire('Gagal', 'Tidak dapat login sebagai user ini.', 'error');
                        });
                }
            });
        }

        function hapusConfirm(codePegawai) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Ingin menghapus data pegawai ini !',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    axios.delete(`/kepegawaian/pegawai/hapusPegawai/${codePegawai}`, {
                            id: codePegawai,
                        }, {
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
                            let errorMessage = 'Terjadi kesalahan saat menghapus data.';

                            if (error.response && error.response.data && error.response.data.message) {
                                errorMessage = error.response.data.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: errorMessage
                            });
                        });
                }
            });
        }
    </script>
@endsection
