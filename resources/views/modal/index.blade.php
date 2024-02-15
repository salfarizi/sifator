@extends('layout.main')
@section('container')
<link href=" https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<div id="pesan" data-flash="{{ session('success') }}"></div>
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modal-halaman-modal">Set Modal Awal</button>
<!-- Modal  Launch Large-->
<div class="modal fade" id="modal-halaman-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Set Modal Awal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="javascript:;">
                @csrf
                @method('put')
                <input type="hidden" name="unique" id="unique" value="{{ $data->unique }}">
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-primary text-white" id="basic-addon1">Rp.</span>
                        <input type="text" class="form-control input-default money" name="modal" id="modal" placeholder="Masukan Modal Awal" value="{{ $data->modal }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="save-data-modal">Tambah Modal Awal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row g-2">
    <div class="col-12 col-sm-6 col-lg-6">
        <div class="card sh-11 hover-scale-up cursor-pointer">
            <div class="h-100 row g-0 card-body align-items-center py-3">
                <div class="col-auto pe-3">
                    <div class="bg-gradient-light sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                        <i class="bi-cash icon-24 text-white"></i>
                    </div>
                </div>
                <div class="col">
                    <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                            <div class="d-flex align-items-center lh-1-25">Modal Awal</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                            <div class="cta-2 text-primary" id="refresh-modal">{{ rupiah($data->modal) }}</div>
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
                        <i data-acorn-icon="wallet" class="text-white" data-acorn-size="25"></i>
                    </div>
                </div>
                <div class="col">
                    <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                            <div class="d-flex align-items-center lh-1-25">Jumlah Asset</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                            <div class="cta-2 text-primary" id="refresh-asset">{{ rupiah($bike_sele) }}</div>
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
                        <i class="bi-bicycle icon-24 text-white"></i>
                    </div>
                </div>
                <div class="col">
                    <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                            <div class="d-flex align-items-center lh-1-25">Jumlah Unit</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                            <div class="cta-2 text-primary" id="refresh-asset">{{ $jumlah_unit }}</div>
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
                        <i class="bi-wallet icon-20 text-white"></i>
                    </div>
                </div>
                <div class="col">
                    <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                            <div class="d-flex align-items-center lh-1-25">Sisa Modal</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                            <div class="cta-2 text-primary" id="refresh-sisa">{{ rupiah($sisa_modal) }}</div>
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
                        <i class="bi-cash-stack icon-20 text-white"></i>
                    </div>
                </div>
                <div class="col">
                    <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                            <div class="d-flex align-items-center lh-1-25">Saldo Bank</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                            <div class="cta-2 text-primary" id="refresh-modal">{{ rupiah($sisa_bank + $laba_kredit) }}</div>
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
                        <i data-acorn-icon="gold" class="text-white" data-acorn-size="22"></i>
                    </div>
                </div>
                <div class="col">
                    <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                            <div class="d-flex align-items-center lh-1-25">Laba</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                            <div class="cta-2 text-primary" id="refresh-laba">{{ rupiah($laba) }}</div>
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
                        <i data-acorn-icon="money-bag" class="text-white" data-acorn-size="25"></i>
                    </div>
                </div>
                <div class="col">
                    <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                            <div class="d-flex align-items-center lh-1-25">Modal + Laba</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                            <div class="cta-2 text-primary" id="refresh-modal">{{ rupiah($data->modal + $laba + $laba_kredit) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class=" col-12 col-sm-6 col-lg-6">
        <div class="card sh-11 hover-scale-up cursor-pointer">
            <div class="h-100 row g-0 card-body align-items-center py-3">
                <div class="col-auto pe-3">
                    <div class="bg-gradient-light sh-5 sw-5 rounded-xl d-flex justify-content-center align-items-center">
                        <i data-acorn-icon="form-check" class="text-white" data-acorn-size="25"></i>
                    </div>
                </div>
                <div class="col">
                    <div class="row gx-2 d-flex align-content-center">
                        <div class="col-12 col-xl d-flex">
                            <div class="d-flex align-items-center lh-1-25">Jumlah Stock Unit + Unit Terjual</div>
                        </div>
                        <div class="col-12 col-xl-auto">
                            <div class="cta-2 text-primary" id="refresh-modal"> {{ $semua_unit }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Simple Money Format --}}
    <script src="/page-script/simple.money.format.js"></script>
    <script src="/page-script/simple.money.format.init.js"></script>
    {{-- !Simple Money Format --}}
    <script src="/page-script/modal.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const flashData = $('#pesan').data('flash');
        if (flashData) {
            Swal.fire(
                'Good job!', flashData, 'success'
            )
        }

    </script>
    @endsection
