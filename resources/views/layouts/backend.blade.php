<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sash – Bootstrap 5  Admin & Dashboard Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords"
        content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('backend/images/brand/favicon.ico') }}" />

    <!-- TITLE -->
    <title>@yield('title')</title>

    <link id="style" href="{{ asset('backend/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/css/dark-style.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/css/transparent-style.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/skin-modes.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/css/icons.css') }}" rel="stylesheet" />
    <link id="theme" rel="stylesheet" type="text/css" media="all"
        href="{{ asset('backend/colors/color1.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('backend/js/jquery.min.js') }}"></script>
    @vite(['resources/js/app.js']);
    <style>
        .swal2-container {
            z-index: 20000 !important;
        }

        .wrap-input100 a.input-group-text:hover i {
            color: #007bff;
            /* warna biru saat hover */
            cursor: pointer;
            /* cursor pointer saat hover */
        }

        .wrap-input100 a.input-group-text i {
            transition: color 0.3s ease;
        }
    </style>
</head>
@php
    $originalUser = \App\Models\User::where('code_user', Session::get('impersonate'))->first();
@endphp

<body class="app sidebar-mini ltr light-mode">

    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="{{ asset('backend/images/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    <!-- /GLOBAL-LOADER -->

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">

            @include('layouts.partials.header')

            @include('layouts.partials.sidebar')

            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <div class="main-container container-fluid">

                        <div class="page-header">
                            <h1 class="page-title">@yield('title-content')</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">@yield('title-content')</li>
                                </ol>
                            </div>
                        </div>
                        @if (Session::has('impersonate') && $originalUser)
                            <div class="alert alert-danger mt-5" role="alert"
                                style="background: #e74c3c; color: #fff">
                                ANDA LOGIN SEBAGAI <u><b>{{ Str::ucfirst(auth()->user()->name) }}</b></u>.
                                <a href="javascript:void(0)" onclick="kembaliKeAkunAsli()" class="badge bg-success p-2"
                                    style="color:#fff">
                                    KLIK DISINI
                                </a>
                                UNTUK KEMBALI KE <b>{{ Str::ucfirst($originalUser->name) }}</b>.
                            </div>
                            <script>
                                function kembaliKeAkunAsli() {
                                    Swal.fire({
                                        title: 'Apakah anda yakin?',
                                        text: 'Ingin kembali ke akun asli?',
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonText: 'Ya, Kembali!',
                                        cancelButtonText: 'Batal'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                                            axios.get('{{ route('impersonate.stop') }}', {
                                                headers: {
                                                    'X-CSRF-TOKEN': token
                                                }
                                            }).then(response => {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Berhasil',
                                                    text: 'Selamat datang kembali.',
                                                    timer: 2000,
                                                    showConfirmButton: false
                                                }).then(() => {
                                                    window.location.href = response.request.responseURL;
                                                });
                                            }).catch(error => {
                                                Swal.fire('Gagal', 'Gagal kembali ke akun asli.', 'error');
                                            });
                                        }
                                    });
                                }
                            </script>
                        @endif

                        @yield('content')

                    </div>
                    <!-- CONTAINER END -->
                </div>
            </div>
            <!--app-content close-->

        </div>

        <!-- FOOTER -->
        <footer class="footer">
            <div class="container">
                <div class="row align-items-center flex-row-reverse">
                    <div class="col-md-12 col-sm-12 text-center">
                        Copyright © 2022 <a href="javascript:void(0)">Sash</a>. Designed with <span
                            class="fa fa-heart text-danger"></span> by <a href="javascript:void(0)"> Spruko </a> All
                        rights reserved.
                    </div>
                </div>
            </div>
        </footer>
        <!-- FOOTER END -->

        <div class="modal fade" id="dinamicModals">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title" id="myModalLabels">Modal Title</h6>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <i class="fa fa-spinner fa-spin"></i> loading ...
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="dinamicModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <i class="fa fa-spinner fa-spin"></i> loading ...
                    </div>
                </div>
            </div>
        </div>

    </div>

    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>
    <script src="{{ asset('backend/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/js/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('backend/js/sticky.js') }}"></script>
    <script src="{{ asset('backend/js/circle-progress.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/peitychart/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/peitychart/peitychart.init.js') }}"></script>
    <script src="{{ asset('backend/plugins/sidebar/sidebar.js') }}"></script>
    {{-- <script src="{{ asset('backend/plugins/p-scroll/perfect-scrollbar.js') }}"></script> --}}
    {{-- <script src="{{ asset('backend/plugins/p-scroll/pscroll.js') }}"></script> --}}
    {{-- <script src="{{ asset('backend/plugins/p-scroll/pscroll-1.js') }}"></script> --}}
    <script src="{{ asset('backend/plugins/chart/Chart.bundle.js') }}"></script>
    <script src="{{ asset('backend/plugins/chart/rounded-barchart.js') }}"></script>
    <script src="{{ asset('backend/plugins/chart/utils.js') }}"></script>
    <script src="{{ asset('backend/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/js/apexcharts.js') }}"></script>
    <script src="{{ asset('backend/plugins/apexchart/irregular-data-series.js') }}"></script>
    <script src="{{ asset('backend/plugins/charts-c3/d3.v5.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/charts-c3/c3-chart.js') }}"></script>
    <script src="{{ asset('backend/js/charts.js') }}"></script>
    <script src="{{ asset('backend/plugins/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('backend/plugins/flot/jquery.flot.fillbetween.js') }}"></script>
    <script src="{{ asset('backend/plugins/flot/chart.flot.sampledata.js') }}"></script>
    <script src="{{ asset('backend/plugins/flot/dashboard.sampledata.js') }}"></script>
    <script src="{{ asset('backend/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('backend/plugins/sidemenu/sidemenu.js') }}"></script>
    <script src="{{ asset('backend/js/index1.js') }}"></script>
    <script src="{{ asset('backend/js/themeColors.js') }}"></script>
    <script src="{{ asset('backend/js/custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0"></script>
    <script>
        function initPasswordToggles() {
            const toggles = [{
                    toggleId: 'Password-toggle1',
                    inputId: 'valPassword'
                },
                {
                    toggleId: 'Password-toggle2',
                    inputId: 'valConfirmPwd'
                }
            ];

            toggles.forEach(({
                toggleId,
                inputId
            }) => {
                const toggleContainer = document.getElementById(toggleId);
                if (!toggleContainer) return;

                const input = document.getElementById(inputId);
                const toggleBtn = toggleContainer.querySelector('a');
                const icon = toggleBtn.querySelector('i');

                toggleBtn.onclick = null; // hapus event lama kalau ada

                toggleBtn.onclick = function(e) {
                    e.preventDefault();
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    icon.classList.toggle('zmdi-eye');
                    icon.classList.toggle('zmdi-eye-off');
                };
            });
        }

        $('#dinamicModals').on('show.bs.modal', function(e) {
            var button = $(e.relatedTarget); // Tombol yang klik
            var modal = $(this);
            var title = button.data('title'); // Ambil dari data-title
            var href = button.data('href'); // Ambil dari data-href

            // Ganti title modal
            modal.find('.modal-title').text(title || 'Default Title');

            // Load konten ke dalam body modal
            modal.find('.modal-body').html('<i class="fa fa-spinner fa-spin"></i> loading ...')
                .load(href, function() {
                    if (typeof initPasswordToggles === "function") {
                        initPasswordToggles();
                    }
                });
        });


        $("#dinamicModal").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            var modal = $(this);

            modal.find(".modal-body").load(link.attr("data-href"), function() {
                initPasswordToggles(); // Panggil fungsi toggle password di sini
            });

            modal.find("#myModalLabel").text(link.attr("data-bs-title"));
        });
    </script>
</body>

</html>
