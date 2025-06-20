@extends('layouts.backend')
@section('title', 'Roles - SIM Pegawai')
@section('title-content', 'Roles')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ __('Data Roles') }}
            @can('tombol tambah role user')
                <div class="card-options">
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#dinamicModals" data-title="Tambah Roles"
                        data-href="{{ route('roles.create') }}" class="btn btn-primary btn-sm" title="tambah roles">
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
                            <th class="border-bottom-0">Roles</th>
                            <th class="border-bottom-0">Permissions</th>
                            <th class="border-bottom-0">Dibuat</th>
                            <th class="border-bottom-0" width="25%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $rowRoles)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucfirst($rowRoles->name) }}</td>
                                <td>{{ $rowRoles->permissions->pluck('name')->implode(',') }}</td>
                                <td>{{ \Carbon\Carbon::parse($rowRoles->created)->format('d M, Y') }}</td>
                                <td>
                                    @can('tombol ubah role user')
                                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#dinamicModals"
                                            data-title="Ubah Roles"
                                            data-href="{{ route('roles.edit', ['roleCode' => $rowRoles->code_role]) }}"
                                            class="btn btn-primary btn-sm" title="ubah roles">
                                            <i class="fe fe-edit-circle"></i> Ubah
                                        </a>
                                    @endcan
                                    @can('tombol hapus role user')
                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger text-white mb-1"
                                            onclick="hapusConfirm('{{ $rowRoles->code_role }}')">
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
                            <th class="border-bottom-0">Roles</th>
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

        function hapusConfirm(roleCode) {
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

                    axios.delete(`/pengaturan/permissions/deletePermissions/${roleCode}`, {
                            id: roleCode,
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
