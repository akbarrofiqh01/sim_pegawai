@extends('layouts.backend')
@section('title', 'Jabatan - SIM Pegawai')
@section('title-content', 'Jabatan')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ __('Data Jabatan') }}
            @can('tombol tambah jabatan')
                <div class="card-options">
                    <a data-href="{{ route('jabatan.create') }}" data-bs-title="Tambah Jabatan" data-bs-remote="false"
                        data-bs-toggle="modal" data-bs-target="#dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false"
                        title="tambah jabatan" class="btn btn-sm btn-primary text-white mb-1">
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
                            <th class="border-bottom-0">Gaji Pokok</th>
                            <th class="border-bottom-0">Dibuat</th>
                            <th class="border-bottom-0" width="25%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jabatan as $dataJabatan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucfirst($dataJabatan->nama_jabatan) }}</td>
                                <td>Rp {{ number_format($dataJabatan->gaji_pokok, 0, ',', '.') }}</td>

                                <td>{{ \Carbon\Carbon::parse($dataJabatan->created)->format('d M, Y') }}</td>
                                <td>
                                    @can('tombol ubah jabatan')
                                        <a data-href="{{ route('jabatan.edit', ['jabatanCode' => $dataJabatan->code_jabatan]) }}"
                                            data-bs-title="Ubah Jabatan" data-bs-remote="false" data-bs-toggle="modal"
                                            data-bs-target="#dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false"
                                            title="ubah jabatan" class="btn btn-sm btn-primary text-white mb-1">
                                            <i class="fe fe-edit"></i> Ubah
                                        </a>
                                    @endcan
                                    @can('tombol hapus jabatan')
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger text-white mb-1"
                                            onclick="hapusConfirm('{{ $dataJabatan->code_jabatan }}')">
                                            <i class="fe fe-trash-2"></i> Hapus
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="border-bottom-0" width="5%">No</th>
                            <th class="border-bottom-0">Jabatan</th>
                            <th class="border-bottom-0">Gaji Pokok</th>
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

        function hapusConfirm(jabatanCode) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Ingin menghapus data jabatan ini !',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    axios.delete(`/kepegawaian/jabatan/hapusJabatan/${jabatanCode}`, {
                            id: jabatanCode,
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
