@extends('layouts.backend')
@section('title', 'Cuti - SIM Pegawai')
@section('title-content', 'Cuti')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ __('Cuti') }}
            @can('tombol tambah cuti')
                <div class="card-options">
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#dinamicModals"
                        data-title="Tambah Pengajuan Cuti" data-href="{{ route('cuti.create') }}" class="btn btn-primary btn-sm"
                        title="tambah pengajuan cuti">
                        <i class="fe fe-plus-circle"></i> Pengajuan Cuti
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
                            <th class="border-bottom-0">Jenis Cuti</th>
                            <th class="border-bottom-0">Tanggal</th>
                            <th class="border-bottom-0">Alasan</th>
                            <th class="border-bottom-0">Status</th>
                            <th class="border-bottom-0">Dibuat</th>
                            <th class="border-bottom-0" width="25%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listttCuti as $dataCuti)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ Str::ucfirst($dataCuti->jenis_cuti) }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($dataCuti->tanggal_mulai)->format('d M, Y') . ' - ' . \Carbon\Carbon::parse($dataCuti->tanggal_selesai)->format('d M, Y') }}
                                </td>
                                <td>
                                    {{ $dataCuti->alasan }}
                                </td>
                                <td>
                                    @if ($dataCuti->status == 'pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @elseif($dataCuti->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($dataCuti->created)->format('d M, Y') }}</td>
                                <td>
                                    @if ($dataCuti->status != 'approved')
                                        @can('tombol ubah cuti')
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#dinamicModals"
                                                data-title="Ubah Pengajuan Cuti"
                                                data-href="{{ route('cuti.edit', ['cutiCode' => $dataCuti->code_cuti_pegawai]) }}"
                                                class="btn btn-primary btn-sm" title="ubah pengajuan cuti">
                                                <i class="fe fe-edit"></i> Ubah
                                            </a>
                                        @endcan
                                        @can('tombol hapus cuti')
                                            <a href="javascript:void(0)" class="btn btn-sm btn-danger text-white "
                                                onclick="hapusConfirm('{{ $dataCuti->code_cuti_pegawai }}')">
                                                <i class="fe fe-trash-2"></i> Hapus
                                            </a>
                                        @endcan
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="border-bottom-0" width="5%">No</th>
                            <th class="border-bottom-0">Jenis Cuti</th>
                            <th class="border-bottom-0">Tanggal</th>
                            <th class="border-bottom-0">Alasan</th>
                            <th class="border-bottom-0">Status</th>
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
                text: 'Ingin menghapus pengajuan cuti ini !',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    axios.delete(`/kepegawaian/cuti/hapusPengajuanCuti/${jabatanCode}`, {
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
