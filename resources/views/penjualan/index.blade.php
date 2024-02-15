@extends('layout.main')
@section('container')
<link href=" https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<div id="pesan" data-flash="{{ session('success') }}"></div>
<div class="row">
    <div class="col-xl-12">
        {{-- DATATABLE CASH --}}
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-md-7">

                </div>
                <!-- Title End -->

                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">


                    <!-- Check Button Start -->
                    <div class="btn-group ms-1 check-all-container">

                    </div>
                    <!-- Check Button End -->
                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
    </div>
</div>
<!-- Title and Top Buttons End -->

<!-- Content Start -->
<div class="data-table-boxed">
    <!-- Controls Start -->
    <div class="row mb-2">
        <!-- Search Start -->
        <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-2 mb-1">

        </div>
        <!-- Search End -->

        <div class="col-sm-12 col-md-7 col-lg-9 col-xxl-10 text-end mb-1">
            <div class="d-inline-block me-0 me-sm-3 float-start float-md-none">

            </div>
            <div class="d-inline-block">
                <!-- Add New Button Start -->
                <button type="button" class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable-penjualanCash" data-bs-toggle="modal" data-bs-target="#modal-transaksi" id="btn-add-data" title="Tambah Data Penjualan">
                    <i data-acorn-icon="plus"></i>
                </button>
                <button type="button" class="btn btn-icon btn-icon-only btn-outline-primary" data-bs-toggle="modal" data-bs-target="#summary_cash" title="Summary Penjualan Cash"><i data-acorn-icon="notebook-1"></i></button>
                {{-- <button class="btn btn-icon btn-icon-only btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Download PDF" type="button">
                    <i data-acorn-icon="download"></i>
                </button> --}}
                <!-- Export Dropdown Start -->
                <div class="d-inline-block datatable-export" data-datatable="#datatableBoxed">

                </div>
                <!-- Export Dropdown End -->


            </div>
        </div>
    </div>
</div>
<!-- Controls End -->

<!-- Table Start -->
<table id="datatableBoxed_penjualan_cash" class="data-table nowrap hover" style="font-family: 'Nunito Sans', sans-serif; font-size: 0.9em ">
    <thead>
        <tr>
            <th class="text-muted text-small text-uppercase">No</th>
            <th class="text-muted text-small text-uppercase">Nama Pembeli</th>
            <th class="text-muted text-small text-uppercase">No Polisi</th>
            <th class="text-muted text-small text-uppercase">Merk</th>
            <th class="text-muted text-small text-uppercase">Warna</th>
            <th class="text-muted text-small text-uppercase">Harga Jual</th>
            <th class="text-muted text-small text-uppercase">Cetak</th>
            <th class="text-muted text-small text-uppercase">Action</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>


<!-- Add Edit Modal Start -->
<div class="modal modal-center fade" id="modal-transaksi" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah/Edit Data Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="javascript:;" enctype="multipart/form-data" id="form-penjualan">
                    <div class="current-id"></div>
                    @csrf
                    <div class="form-floating mb-3 w-100" id="no-polisi">
                        <select id="no_polisi" name="no_polisi" class="form-control no-polisi" placeholder="Masukan No Polisi">
                            <option label="&nbsp;"></option>
                            @foreach ($no_polisi as $row)
                            <option value="{{ $row->id }}">{{ $row->no_polisi }}</option>
                            @endforeach
                        </select>
                        <label>No Polisi <span class="text-danger"> *</span></label>
                    </div>
                    <div class="form-floating mb-3" id="current-no-polisi">


                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="merk" id="merk" class="form-control" style="background-color: rgba(215, 218, 227, 0.3)" disabled>
                        <label class="text-label" for="merk">Merk</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="warna" id="warna" class="form-control" style="background-color: rgba(215, 218, 227, 0.3)" disabled>
                        <label>Warna</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="tahun_pembuatan" id="tahun_pembuatan" disabled style="background-color: rgba(215, 218, 227, 0.3)">
                        <label for="tahun_pembuatan">Tahun Pembuatan</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control money" name="harga_beli" id="harga_beli" style="background-color: rgba(215, 218, 227, 0.3)" readonly>
                        <label for="harga_beli">Harga Beli</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="nik" id="nik" placeholder="Masukan NIK">
                        <label class="text-label" for="nik">NIK<span class="text-danger"> *</label></span>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="nama_pembeli" id="nama_pembeli" placeholder="Masukan Nama">
                        <label class="text-label" for="nama">Nama<span class="text-danger"> *</label></span>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea name="alamat" id="alamat" class="form-control" placeholder="Masukan Alamat"></textarea>
                        <label class="text-label" for="alamat">Alamat<span class="text-danger"> *</label></span>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="no_telepon" id="no_telepon" placeholder="Masukan No Telepon">
                        <label class="text-label" for="no_telepon">No Telepon<span class="text-danger"> *</label></span>
                    </div>
                    <div class="input-group mb-3">
                        <input type="file" class="form-control" id="photo-ktp" name="photo-ktp" onchange="previewImageKTP()">
                        <label class="input-group-text" for="photo-ktp">Upload Photo KTP</label>
                    </div>
                    <div class="col-lg-12 mb-3 text-center" id="img-ktp">
                        <img src="/storage/ktp/default.png" alt="" class="img-fluid" width="200px">
                        <br>
                        <input type="hidden" name="photo_ktp" id="photo_ktp">
                        <input type="hidden" name="old_ktp" id="old_ktp">
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control date-picker" placeholder="Masukan Tanggal Penjualan" name="tanggal_jual" id="tanggal_jual" />
                        <label class="text-label" for="tanggal_jual">Tanggal Penjualan<span class="text-danger"> *</label></span>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control money" placeholder="Masukan Harga Jual" name="harga_jual" id="harga_jual">
                        <label class="text-label" for="harga_jual">Harga Jual<span class="text-danger"> *</label></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="btn-action">

            </div>
        </div>
    </div>
</div>
<!-- Add Edit Modal End -->
<div class="modal fade" id="summary_cash" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4" id="exampleModalFullscreenLabel">Summary Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mt-5">
                        <label class="text-label" for="laporan_perbulan">Laporan Penjualan Perbulan</label>
                        <form action="/penjualanSelectMonthCash" method="post">
                            @csrf
                            <div class="input-group mt-2">
                                <label class="input-group-text" for="inputGroupSelect01"><i class="bi-calendar2-month-fill icon-16 text-primary"></i></label>
                                <select name="bulan" class="form-select rounded-md-bottom-end rounded-md-top-end" id="inputGroupSelect04" aria-label="Example select with button addon" required>
                                    <option selected disabled value="">Pilih Bulan</option>
                                    <option value="01">Januari</option>
                                    <option value="02">Februari</option>
                                    <option value="03">Maret</option>
                                    <option value="04">April</option>
                                    <option value="05">Mei</option>
                                    <option value="06">Juni</option>
                                    <option value="07">Juli</option>
                                    <option value="08">Agustus</option>
                                    <option value="09">September</option>
                                    <option value="10">Oktober</option>
                                    <option value="11">November</option>
                                    <option value="12">Desember</option>
                                </select>
                                <span class="mx-2"></span>
                                <button class="btn btn-primary rounded-md-bottom-start rounded-md-top-start" type="submit">
                                    <i data-acorn-icon="download" data-acorn-size="18"></i>
                                    PDF
                                </button>
                                <button class="btn btn-secondary" type="button">
                                    <i data-acorn-icon="print" data-acorn-size="18"></i>
                                    Print
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 mt-5">
                        <form action="/penjualanDateCash" method="post">
                            @csrf
                            <label class="text-label mb-2" for="laporan_pertanggal">Laporan Penjualan Pertanggal</label>
                            <div class="input-daterange input-group">
                                <input type="date" class="date form-control" name="tanggal_awal" placeholder="Tanggal Awal" required />
                                <span class="mx-2"></span>
                                <input type="date" class="date form-control" name="tanggal_akhir" placeholder="Tanggal Akhir" required />
                                <span class="mx-2"></span>
                                <button class="btn btn-primary rounded-md-bottom-start rounded-md-top-start" type="submit">
                                    <i data-acorn-icon="download" data-acorn-size="18"></i>
                                    PDF
                                </button>
                                <button type="button" class="btn btn-secondary">
                                    <i data-acorn-icon="print" data-acorn-size="18"></i>
                                    Print
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 mt-5">
                        <label class="text-label" for="laporan_pertanggal">Laporan Penjualan Hari Ini</label>
                        <div class="input-daterange input-group mt-2">
                            <a href="/penjualanDayCash" target="_blank" class="btn btn-primary rounded-md-bottom-start rounded-md-top-start">
                                <i data-acorn-icon="download" data-acorn-size="18"></i>
                                PDF
                            </a>
                            <button type="button" class="btn btn-secondary">
                                <i data-acorn-icon="print" data-acorn-size="18"></i>
                                Print
                            </button>
                        </div>
                    </div>
                    <div class="col-12 mt-5">
                        <label class="text-label" for="laporan_pertanggal">Laporan Penjualan Minggu Ini</label>
                        <div class="input-daterange input-group mt-2">
                            <a href="/penjualanWeekCash" class="btn btn-primary rounded-md-bottom-start rounded-md-top-start">
                                <i data-acorn-icon="download" data-acorn-size="18"></i>
                                PDF
                            </a>
                            <button type="button" class="btn btn-secondary">
                                <i data-acorn-icon="print" data-acorn-size="18"></i>
                                Print
                            </button>
                        </div>
                    </div>
                    <div class="col-12 mt-5">
                        <label class="text-label" for="laporan_pertanggal">Laporan Penjualan Bulan Ini</label>
                        <div class="input-daterange input-group mt-2">
                            <a href="/penjualanMonthCash" class="btn btn-primary rounded-md-bottom-start rounded-md-top-start">
                                <i data-acorn-icon="download" data-acorn-size="18"></i>
                                PDF
                            </a>
                            <button type="button" class="btn btn-secondary">
                                <i data-acorn-icon="print" data-acorn-size="18"></i>
                                Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
{{-- Modal Detail Penjualan --}}
<div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="accordion accordion-flush" id="data-individu">
                    <div class="accordion-item">
                        <div class="accordion-header" id="flush-headingZero">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseZero" aria-expanded="false" aria-controls="flush-collapseZero">
                                Data Pembeli
                            </button>
                        </div>
                        <div id="flush-collapseZero" class="accordion-collapse collapse" aria-labelledby="flush-headingZero" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="table-responsive">
                                    <table class="table header-border table-responsive-sm table-striped" id="table_konsumen">
                                        <tbody>
                                            <tr>
                                                <td>NIK</td>
                                                <td>:</td>
                                                <td><span id="nik-detail"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Nama</td>
                                                <td>:</td>
                                                <td><span id="nama"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Nomor Telepon</td>
                                                <td>:</td>
                                                <td><span id="no-telepon"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Alamat</td>
                                                <td>:</td>
                                                <td><span id="alamat-detail"></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                Data Motor
                            </button>
                        </div>
                        <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="table-responsive">
                                    <table class="table header-border table-responsive-sm table-striped">
                                        <tbody>
                                            <tr>
                                                <td>No Polisi</td>
                                                <td>:</td>
                                                <td><span id="no-polisi-detail"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Merk</td>
                                                <td>:</td>
                                                <td><span id="merk-detail"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Type/Model</td>
                                                <td>:</td>
                                                <td><span id="tipe"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Warna</td>
                                                <td>:</td>
                                                <td><span id="warna-detail"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Tahun Pembuatan</td>
                                                <td>:</td>
                                                <td><span id="tahun-pembuatan"></span></td>
                                            </tr>
                                            <tr>
                                                <td>No Rangka</td>
                                                <td>:</td>
                                                <td><span id="no-rangka"></span></td>
                                            </tr>
                                            <tr>
                                                <td>No Mesin</td>
                                                <td>:</td>
                                                <td><span id="no-mesin"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Nomor BPKB</td>
                                                <td>:</td>
                                                <td><span id="bpkb"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Nama BPKB</td>
                                                <td>:</td>
                                                <td><span id="nama-bpkb"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Alamat BPKB</td>
                                                <td>:</td>
                                                <td><span id="alamat-bpkb"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Berlaku Sampai</td>
                                                <td>:</td>
                                                <td><span id="berlaku-sampai"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Perpanjang STNK</td>
                                                <td>:</td>
                                                <td><span id="perpanjang-stnk"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Foto BPKB</td>
                                                <td>:</td>
                                                <td><span id="foto-bpkb"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Foto STNK</td>
                                                <td>:</td>
                                                <td><span id="foto-stnk"></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <div class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                Data Penjualan
                            </button>
                        </div>
                        <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="table-responsive">
                                    <table class="table header-border table-responsive-sm table-striped">
                                        <tbody>
                                            <tr>
                                                <td>Tanggal Jual</td>
                                                <td>:</td>
                                                <td><span id="tanggal-jual"></span></td>
                                            </tr>
                                            <tr>
                                                <td>Harga Jual</td>
                                                <td>:</td>
                                                <td><span id="harga-jual"></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal  Launch Large Gambar-->
<div class="modal fade" id="modal-image" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="judul-modal-photo"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="img-photo" class="d-flex justify-content-center align-items-center"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
{{-- Simple Money Format --}}
<script src="/page-script/simple.money.format.js"></script>
<script src="/page-script/simple.money.format.init.js"></script>
{{-- !Simple Money Format --}}
<script src="/page-script/penjualan.js"></script>
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
