@extends('layouts.backend')
@section('title', 'Permissions - SIM Pegawai')
@section('title-content', 'Permissions')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ __('Data Permissions') }}
            @can('tombol tambah permissions user')
                <div class="card-options">
                    <a data-href="{{ route('permissions.create') }}" data-bs-title="Tambah Permissions" data-bs-remote="false"
                        data-bs-toggle="modal" data-bs-target="#dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false"
                        title="tambah permissions" class="btn btn-sm btn-primary text-white mb-1">
                        <i class="fe fe-plus"></i> Tambah
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
                            <th class="border-bottom-0">Permissions</th>
                            <th class="border-bottom-0">Dibuat</th>
                            <th class="border-bottom-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataPermissions as $permissions)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $permissions->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($permissions->created)->format('d M, Y') }}</td>
                                <td>
                                    @can('tombol ubah permissions user')
                                        <a data-href="{{ route('permissions.edit', ['permissionscode' => $permissions->code_permissions]) }}"
                                            data-bs-title="Ubah Permissions" data-bs-remote="false" data-bs-toggle="modal"
                                            data-bs-target="#dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false"
                                            title="ubah permissions" class="btn btn-sm btn-primary text-white mb-1">
                                            <i class="fe fe-edit"></i> Edit
                                        </a>
                                    @endcan
                                    @can('tombol hapus permissions user')
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger text-white mb-1"
                                            onclick="hapusConfirm('{{ $permissions->code_permissions }}')">
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
                            <th class="border-bottom-0">Permissions</th>
                            <th class="border-bottom-0">Dibuat</th>
                            <th class="border-bottom-0">Aksi</th>
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

        function hapusConfirm(userId) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Ingin menghapus permissions ini !',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    axios.delete(`/pengaturan/permissions/deletePermissions/${userId}`, {
                            id: userId,
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
