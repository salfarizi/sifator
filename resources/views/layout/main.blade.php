<?php 
use App\Models\Setting; 
use App\Models\Access;
use App\Models\Role;
$role = Role::where('name', auth()->user()->roles)->first();

$penjualan = Access::where('role_unique', $role->unique)->where('menu_name', 'PENJUALAN')->first();
$pembelian = Access::where('role_unique', $role->unique)->where('menu_name', 'PEMBELIAN')->first();
$modal = Access::where('role_unique', $role->unique)->where('menu_name', 'MODAL')->first();
$register = Access::where('role_unique', $role->unique)->where('menu_name', 'REGISTER ORDER')->first();
$laporan = Access::where('role_unique', $role->unique)->where('menu_name', 'LAPORAN')->first();
$setting = Access::where('role_unique', $role->unique)->where('menu_name', 'SETTING')->first();
$master = Access::where('role_unique', $role->unique)->where('menu_name', 'MASTER')->first();
?>
<!DOCTYPE html>
<html lang="en" data-footer="true" data-override='{"attributes": {"placement": "vertical" }}'>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>{{ $title }}</title>
    <meta name="description" content="A vertical menu that never gets into semi-hidden state and switches to mobile view directly after desktop view." />
    <!-- Favicon Tags Start -->
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/img/favicon/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/img/favicon/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/img/favicon/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/img/favicon/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon-precomposed" sizes="60x60" href="/img/favicon/apple-touch-icon-60x60.png" />
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="/img/favicon/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="/img/favicon/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="/img/favicon/apple-touch-icon-152x152.png" />
    <link rel="icon" type="image/png" href="/img/favicon/favicon-196x196.png" sizes="196x196" />
    <link rel="icon" type="image/png" href="/img/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/png" href="/img/favicon/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="/img/favicon/favicon-16x16.png" sizes="16x16" />
    <link rel="icon" type="image/png" href="/img/favicon/favicon-128.png" sizes="128x128" />
    <meta name="application-name" content="&nbsp;" />
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta name="msapplication-TileImage" content="/img/favicon/mstile-144x144.png" />
    <meta name="msapplication-square70x70logo" content="/img/favicon/mstile-70x70.png" />
    <meta name="msapplication-square150x150logo" content="/img/favicon/mstile-150x150.png" />
    <meta name="msapplication-wide310x150logo" content="/img/favicon/mstile-310x150.png" />
    <meta name="msapplication-square310x310logo" content="/img/favicon/mstile-310x310.png" />
    <!-- Favicon Tags End -->
    <!-- Icon Bootstrap Start -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <!-- Icon Bootstrap End -->
    <!-- Font Tags Start -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/font/CS-Interface/style.css" />
    <!-- Font Tags End -->
    <!-- Vendor Styles Start -->
    <link rel="stylesheet" href="/css/vendor/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/vendor/OverlayScrollbars.min.css" />

    <link rel="stylesheet" href="/css/vendor/select2.min.css" />

    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />

    {{-- CROPPER --}}
    <link rel="stylesheet" href="/css/cropper/cropper.min.css">
    <script src="/js/cropper/cropper.min.js"></script>
    <!-- Vendor Styles End -->

    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />

    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />

    <link rel="stylesheet" href="/css/vendor/tagify.css" />

    <!-- Template Base Styles Start -->
    <link rel="stylesheet" href="/css/styles.css" />
    <!-- Template Base Styles End -->

    <link rel="stylesheet" href="/css/main.css" />
    <script src="/js/base/loader.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>

<body>
    <div id="root">
        <div id="nav" class="nav-container d-flex" data-vertical-unpinned="1200" data-vertical-mobile="1200" data-disable-pinning="true">
            <div class="nav-content d-flex">
                <!-- Logo Start -->

                <a href="/home">
                    <!-- Logo can be added directly -->
                    <img src="/img/logo/smac white transparent.png" alt="logo" style="width: 180px;" />

                    {{-- <!-- Or added via css to provide different ones for different color themes -->
                    <div class="img"></div> --}}
                </a>
                @if(Setting::count() > 0)
                <h6 class="cta-4 mt-1 text-white">{{ ucwords(strtolower(Setting::first()->nama_toko)) }}</h6>
                @else
                <h6 class="cta-4 mt-1 text-white">SIFATOR</h6>
                @endif

                <!-- Logo End -->

                @include('partial.sidebar')

                <main>
                    <div class="container">
                        <!-- Title and Top Buttons Start -->
                        <div class="page-title-container">
                            <div class="row">
                                <h6 class="cta-1 mt-1 text-primary"><b>Sistem Informasi Riffat Jaya Motor</b></h6>
                                <h1 class="mt-5 pb-0 display-4">{{ $judul }}</h1>
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <a href="#" class="muted-link">
                                            <span class="align-middle me-2">{{ $breadcumb1 }}</span>
                                            <i data-acorn-icon="chevron-right" data-acorn-size="13"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="/" class="">
                                            <span class="align-middle me-2">{{ $breadcumb2 }}</span>

                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Title and Top Buttons End -->

                        <!-- Content Start -->
                        @yield('container')
                        <!-- Content End -->
                    </div>
                </main>
                <!-- Layout Footer Start -->
                {{-- <footer>
            <div class="footer-content">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <p class="mb-0 text-muted text-medium">Colored Strategies 2021</p>
                        </div>
                        <div class="col-sm-6 d-none d-sm-block">
                            <ul class="breadcrumb pt-0 pe-0 mb-0 float-end">
                                <li class="breadcrumb-item mb-0 text-medium">
                                    <a href="https://1.envato.market/BX5oGy" target="_blank" class="btn-link">Review</a>
                                </li>
                                <li class="breadcrumb-item mb-0 text-medium">
                                    <a href="https://1.envato.market/BX5oGy" target="_blank" class="btn-link">Purchase</a>
                                </li>
                                <li class="breadcrumb-item mb-0 text-medium">
                                    <a href="https://acorn-html-docs.coloredstrategies.com/" target="_blank" class="btn-link">Docs</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer> --}}
                <!-- Layout Footer End -->
            </div>
            <!-- Vendor Scripts Start -->
            {{-- <script src="/js/vendor/jquery-3.5.1.min.js"></script> --}}
            <script src="/js/vendor/bootstrap.bundle.min.js"></script>
            <script src="/js/vendor/OverlayScrollbars.min.js"></script>
            <script src="/js/vendor/autoComplete.min.js"></script>
            <script src="/js/vendor/clamp.min.js"></script>

            <script src="/icon/acorn-icons.js"></script>
            <script src="/icon/acorn-icons-interface.js"></script>
            <script src="/icon/acorn-icons-commerce.js"></script>

            <script src="/js/cs/scrollspy.js"></script>

            <script src="/js/cs/responsivetab.js"></script>

            <script src="/js/cs/wizard.js"></script>

            <script src="/js/vendor/select2.full.min.js"></script>

            <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>

            <script src="/js/vendor/datepicker/locales/bootstrap-datepicker.id.min.js"></script>

            <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>

            <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>

            <script src="/js/vendor/bootstrap-submenu.js"></script>

            <script src="/js/vendor/datatables.min.js"></script>

            <script src="/js/vendor/mousetrap.min.js"></script>

            <script src="/js/vendor/tagify.min.js"></script>

            <!-- Vendor Scripts End -->

            <!-- Template Base Scripts Start -->
            <script src="/js/base/helpers.js"></script>
            <script src="/js/base/globals.js"></script>
            <script src="/js/base/nav.js"></script>
            <script src="/js/base/search.js"></script>
            <script src="/js/base/settings.js"></script>
            <!-- Template Base Scripts End -->
            <!-- Page Specific Scripts Start -->

            <script src="/js/components/navs.js"></script>

            <script src="/js/forms/wizards.js"></script>

            <script src="/js/cs/datatable.extend.js"></script>

            <script src="/js/forms/controls.select2.js"></script>

            <script src="/js/forms/controls.datepicker.js"></script>

            <script src="/js/forms/layouts.js"></script>
            <script src="/js/common.js"></script>
            <script src="/js/scripts.js"></script>
            <!-- Page Specific Scripts End -->
</body>
</html>
