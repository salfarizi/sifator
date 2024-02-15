@extends('layout.main')
@section('container')
<link href=" https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<div id="pesan" data-flash="{{ session('success') }}"></div>
<div class="row g-2">
    <div class="col-12 col-sm-6 col-lg-6">
        <div class="card sh-11 hover-scale-up cursor-pointer">
            <div class="h-100 row g-0 card-body align-items-center py-3">
                <div class="col-auto pe-3">
                    <div class="bg-gradient-light sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                        <i class="bi-bicycle icon-24 text-white"></i>
                    </div>
                </div>
                <div class="col">
                    <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                            <div class="d-flex align-items-center lh-1-25">Total Motor</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                            <div class="cta-2 text-primary">{{$total_jual}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-6">
        <div class="card sh-11 hover-scale-up cursor-pointer">
            <div class="h-100 row g-0 card-body align-items-center py-3">
                <div class="col-auto pe-3">
                    <div class="bg-gradient-light sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                        <i class="bi-cart-fill icon-20 text-white"></i>
                    </div>
                </div>
                <div class="col">
                    <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                            <div class="d-flex align-items-center lh-1-25">Total Pembelian</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                            <div class="cta-2 text-primary">{{$semua_unit}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <!-- Custom Legend Chart Start -->
    <div class="col-12 col-xl-6">
        <section class="scroll-section" id="customLegendBarTitle">
            <div class="card mb-5">
                <div class="card-header">
                    <h4 class="">Detail Penjualan</h4>
                    <div class="d-flex">
                        <div class="dropdown-as-select me-3" data-setActive="false" data-childSelector="span">
                            <a class="pe-0 pt-0 align-top lh-1 dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                <span class="small-title"></span>
                            </a>
                            <div class="dropdown-menu font-standard">
                                <div class="nav flex-column" role="tablist">
                                    <a class="active dropdown-item text-medium" href="#" aria-selected="true" role="tab">Perminggu</a>
                                    <a class="dropdown-item text-medium" href="#" aria-selected="false" role="tab">Perbulan</a>
                                    <a class="dropdown-item text-medium" href="#" aria-selected="false" role="tab">Pertahun</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="custom-legend-container mb-3 pb-3 d-flex flex-row"></div>
                    <template class="custom-legend-item">
                        <a href="#" class="d-flex flex-row g-0 align-items-center me-5">
                            <div class="pe-2">
                                <div class="icon-container border sw-5 sh-5 rounded-xl d-flex justify-content-center align-items-center">
                                    <i class="icon"></i>
                                </div>
                            </div>
                            <div>
                                <div class="text p mb-0 d-flex align-items-center text-small text-muted">Title</div>
                                <div class="value cta-4">Value</div>
                            </div>
                        </a>
                    </template>
                    <div class="sh-30">
                        <canvas id="customLegendBarChart"></canvas>
                        <div class="custom-tooltip position-absolute bg-foreground rounded-md border border-separator pe-none p-3 d-flex z-index-1 align-items-center opacity-0 basic-transform-transition">
                            <div class="icon-container border d-flex align-middle align-items-center justify-content-center align-self-center rounded-xl sh-5 sw-5 rounded-xl me-3">
                                <span class="icon"></span>
                            </div>
                            <div>
                                <span class="text d-flex align-middle text-muted align-items-center text-small">Cash</span>
                                <span class="value d-flex align-middle align-items-center cta-4">300</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Custom Legend Chart End -->
    <!-- Area Chart Start -->
    <div class="col-12 col-xl-6">
        <div class="card mb-5">
            <div class="card-header">
                <h4 class="">Detail Pembelian</h4>
                <div class="d-flex">
                    <div class="dropdown-as-select me-3" data-setActive="false" data-childSelector="span">
                        <a class="pe-0 pt-0 align-top lh-1 dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                            <span class="small-title"></span>
                        </a>
                        <div class="dropdown-menu font-standard">
                            <div class="nav flex-column" role="tablist">
                                <a class="active dropdown-item text-medium" href="#" aria-selected="true" role="tab">Perminggu</a>
                                <a class="dropdown-item text-medium" href="#" aria-selected="false" role="tab">Perbulan</a>
                                <a class="dropdown-item text-medium" href="#" aria-selected="false" role="tab">Pertahun</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="sh-37">
                    <canvas id="areaChart"></canvas>
                </div>
            </div>
        </div>
    </div> --}}
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const flashData = $('#pesan').data('flash');
    if (flashData) {
        Swal.fire(
            'Berhasil!', flashData, 'success'
        )
    }

</script>
<script src="/js/vendor/Chart.bundle.min.js"></script>
<script src="/js/cs/charts.extend.js"></script>

<script src="/js/plugins/charts.js"></script>
@endsection
