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
                <button type="button" class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable-pemohon" data-bs-toggle="modal" data-bs-target="#modal-pemohon" id="btn-add-data" title="Tambah Reg Order Kredit">
                    <i data-acorn-icon="plus"></i>
                </button>
                {{-- <button type="button" class="btn btn-icon btn-icon-only btn-outline-primary" data-bs-toggle="modal" data-bs-target="#summary_regorderkredit" title="Summary Reg Order Kredit"><i data-acorn-icon="notebook-1"></i></button> --}}
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
<table id="datatableBoxed_reg_order_kredit" class="data-table nowrap hover" style="font-family: 'Nunito Sans', sans-serif; font-size: 0.9em ">
    <thead>
        <tr>
            <th class="text-muted text-small text-uppercase">No</th>
            <th class="text-muted text-small text-uppercase">No Register</th>
            <th class="text-muted text-small text-uppercase">Nama Nasabah</th>
            <th class="text-muted text-small text-uppercase">Alamat</th>
            <th class="text-muted text-small text-uppercase">No Telepon</th>
            <th class="text-muted text-small text-uppercase">Action</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
<!-- Add Edit Modal Start -->
<div class="modal modal-center fade" id="modal-pemohon" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Pemohon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="javascript:;">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="mb-3 form-floating">
                                <input type="text" name="nik" id="nik" class="form-control @error('nik')is-invalid @enderror" value="{{ old('nik') }}" placeholder="Masukan NIK">
                                @error('nik')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="nik">NIK Pemohon<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 form-floating">
                                <input type="text" name="nama_pembeli" id="nama_pembeli" class="form-control @error('nama_pembeli')is-invalid @enderror" value="{{ old('nama_pembeli') }}" placeholder="Masukan Nama Pemohon">
                                @error('nama_pembeli')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="nama_pembeli">Nama Pemohon<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 form-floating">
                                <select id="jenis_kelamin" name="jenis_kelamin">
                                    <option label=""></option>
                                    <option value="Laki-Laki">Laki - Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                <label class="text-label" for="jenis_kelamin">Jenis Kelamin Pemohon<span class="text-danger"> *</label></span>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="mb-3 form-floating">
                                <textarea class="form-control @error('alamat')is-invalid @enderror" rows="3" name="alamat" id="alamat" placeholder="Masukan Alamat">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label for="alamat">Alamat Pemohon<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="mb-3 form-floating">
                                <input type="text" name="no_telepon" id="no_telepon" class="form-control @error('no_telepon')is-invalid @enderror" value="{{ old('no_telepon') }}" placeholder="Masukan No HP Pemohon">
                                @error('no_telepon')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="no_telepon">No HP Pemohon<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 form-floating">
                                <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control @error('tempat_lahir')is-invalid @enderror" value="{{ old('tempat_lahir') }}" placeholder="Masukan Tempat Lahir">
                                @error('tempat_lahir')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="tempat_lahir">Tempat Lahir Pemohon<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 form-floating">
                                <input type="text" name="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir')is-invalid @enderror" value="{{ old('tanggal_lahir') }}" placeholder="Masukan Tanggal Lahir">
                                @error('tanggal_lahir')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="tanggal_lahir">Tanggal Lahir Pemohon<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" name="photo-ktp" id="photo-ktp" onchange="previewImageKTP()">
                                <label class="input-group-text" for="photo-ktp">Upload Foto KTP</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 text-center" id="img-ktp">
                        <div class="col-md-12">
                            <div class="mb-3 form-floating">
                                <img src="/storage/ktp/default.png" alt="" class="img-fluid" width="200px">
                                <br>
                                <input type="hidden" name="photo_ktp" id="photo_ktp">
                                <input type="hidden" name="old_ktp" id="old_ktp">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="btn-action">
            </div>
        </div>
    </div>
</div>
<!-- Add Edit Modal End -->
<!-- Add Edit Modal Start -->
<div class="modal modal-center fade" id="modal-regorderkredit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Register Order Kredit</h5>
                <button type="button" class="btn-close" id="btn-close-list" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="javascript:;">
                    @csrf
                    {{-- Form Hidden --}}
                    <input type="hidden" name="unique_no_reg" id="unique_no_reg" value="0">
                    <input type="hidden" name="buyer_id" id="buyer_id">
                    <input type="hidden" name="current_unique" id="current_unique">
                    {{-- !Form Hidden --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3 form-floating">
                                <input type="text" name="nama_nasabah" id="nama_nasabah" class="form-control" disabled>
                                <label class="text-label" for="nama_nasabah">Nama Nasabah<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-floating">
                                <input type="text" name="no_telepon_nasabah" id="no_telepon_nasabah" class="form-control" disabled>
                                <label class="text-label" for="no_telepon_nasabah">No Telepon Nasabah<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="mb-3 form-floating">
                                <textarea class="form-control @error('alamat_nasabah')is-invalid @enderror" rows="3" name="alamat_nasabah" id="alamat_nasabah" disabled>{{ old('alamat_nasabah') }}</textarea>
                                @error('alamat_nasabah')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label for="alamat_nasabah" style="background-color: #eeeeee">&nbsp;&nbsp;Alamat Nasabah<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="mb-3 form-floating">
                                <input type="text" name="nama_dealer" id="nama_dealer" class="form-control @error('nama_dealer')is-invalid @enderror" value="{{ old('nama_dealer') }}" placeholder="Masukan Nama Dealer">
                                @error('nama_dealer')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="dealer">Nama Dealer<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 form-floating">
                                <input type="text" name="cmo" id="cmo" class="form-control" placeholder="Masukan cmo">
                                <label class="text-label" for="CMO Leasing">CMO Leasing<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 form-floating">
                                <input type="text" name="pic" id="pic" class="form-control @error('pic')is-invalid @enderror" value="{{ old('pic') }}" placeholder="Masukan Sales/PIC Showroom">
                                @error('pic')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="pic">Sales/PIC Showroom<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 d-none" id="view-jenis-transaksi">
                        <div class="col-md-6">
                            <div class="mb-3 form-floating">
                                <input type="text" id="view_jenis_transaksi" class="form-control" disabled>
                                <label class="text-label">Jenis Transksi</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-floating">
                                <input type="text" id="view_via" class="form-control" disabled>
                                <label class="text-label">Kredit Via Leasing</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3 form-floating w-100" id="select-jenis-transaksi">
                                <select id="jenis_transaksi" name="jenis_transaksi">
                                    <option label="&nbsp;"></option>
                                    <option value="CASH">Cash</option>
                                    <option value="KREDIT">Kredit</option>
                                </select>
                                <label class="text-label" for="jenis_transaksi">Jenis Transaksi<span class="text-danger"> *</label></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-floating w-100" id="select-via">
                                <select id="kredit_via_leasing" name="via">
                                    <option label="&nbsp;"></option>
                                    <option value="MUF">MUF</option>
                                    <option value="ADIRA">Adira</option>
                                    <option value="CASH">Cash</option>
                                </select>
                                <label class="text-label" for="via">Kredit Via Leasing<span class="text-danger"> *</label></span>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="mb-3 form-floating w-100">
                                <input type="text" name="merk" id="merk" class="form-control @error('merk')is-invalid @enderror" value="{{ old('merk') }}" placeholder="Masukan Merk Motor">
                                @error('merk')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="merk">Merk Motor<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 form-floating w-100">
                                <input type="text" name="type" id="type" class="form-control @error('type')is-invalid @enderror" value="{{ old('type') }}" placeholder="Masukan Tipe Motor">
                                @error('type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="type">Tipe Motor<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 form-floating w-100">
                                <input type="text" name="tahun_pembuatan" id="tahun_pembuatan" class="form-control @error('tahun_pembuatan')is-invalid @enderror" value="{{ old('tahun_pembuatan') }}" placeholder="Masukan Tahun Kendaraan">
                                @error('tahun_pembuatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="tahun_pembuatan">Tahun Kendaraan<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="mb-3 form-floating w-100">
                                <input type="text" name="otr" id="otr" class="form-control money @error('otr')is-invalid @enderror" value="{{ old('otr') }}" placeholder="Masukan OTR">
                                @error('otr')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="otr">OTR<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 form-floating w-100">
                                <input type="text" name="dp_po" id="dp_po" class="form-control money @error('dp_po')is-invalid @enderror" value="{{ old('dp_po') }}" placeholder="Masukan DP PO">
                                @error('dp_po')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="dp_po">DP PO<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 form-floating w-100">
                                <input type="text" name="pencairan" id="pencairan" class="form-control money @error('pencairan')is-invalid @enderror" value="{{ old('pencairan') }}" placeholder="Masukan Pencairan">
                                @error('pencairan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="pencairan">Pencairan<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="mb-3 form-floating w-100">
                                <input type="text" name="dp" id="dp" class="form-control money @error('dp')is-invalid @enderror" value="{{ old('dp') }}" placeholder="Masukan DP Bayar">
                                @error('dp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="dp">DP Bayar<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 form-floating w-100">
                                <input type="text" name="angsuran" id="angsuran" class="form-control money @error('angsuran')is-invalid @enderror" value="{{ old('angsuran') }}" placeholder="Masukan Angsuran">
                                @error('angsuran')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="angsuran">Angsuran<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 form-floating w-100">
                                <input type="text" name="tenor" id="tenor" class="form-control @error('tenor')is-invalid @enderror" value="{{ old('tenor') }}" placeholder="Masukan Tenor">
                                @error('tenor')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="tenor">Tenor<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                    </div>
                </form>
                {{-- Table --}}
                <br>
                <hr>
                <br>
                <!-- Table Start -->
                <table id="datatableBoxed_reg_order_list" class="data-table nowrap hover" style="font-family: 'Nunito Sans', sans-serif; font-size: 0.9em ">
                    <thead>
                        <tr>
                            <th class="text-muted text-small text-uppercase">No</th>
                            <th class="text-muted text-small text-uppercase">Nama Dealer</th>
                            <th class="text-muted text-small text-uppercase">Kredit Via Leading</th>
                            <th class="text-muted text-small text-uppercase">Merk Motor</th>
                            <th class="text-muted text-small text-uppercase">Tipe</th>
                            <th class="text-muted text-small text-uppercase">Status</th>
                            <th class="text-muted text-small text-uppercase">Ubah Status</th>
                            <th class="text-muted text-small text-uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer" id="btn-action-list-order"></div>
        </div>
    </div>
</div>
<!-- Add Edit Modal End -->
<div class="modal fade" id="summary_regorderkredit" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4" id="exampleModalFullscreenLabel">Summary Reg Order Kredit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

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
<script src="/page-script/regorderkredit.js"></script>
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
