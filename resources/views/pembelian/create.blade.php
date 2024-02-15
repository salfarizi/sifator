@extends('layout.main')
@section('container')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />
<div id="pesan" data-flash="{{ session('error') }}"></div>
<div class="row">
    <div class="col-xl-12 col-xxl-12">
        <div class="card-body h-auto">
            <!-- Basic Start -->
            <div class="card mb-5 wizard" id="wizardBasic">
                <div class="card-header border-0 pb-0">
                    <ul class="nav nav-tabs justify-content-center" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-center" href="#data_konsumen" role="tab">
                                <div class="mb-1 title d-none d-sm-block">Data Konsumen</div>
                                <div class="text-small description d-none d-md-block">Tambah Data Konsumen</div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-center" href="#data_motor" role="tab">
                                <div class="mb-1 title d-none d-sm-block">Data Motor</div>
                                <div class="text-small description d-none d-md-block">Tambah Data Motor</div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-center" href="#harga" role="tab">
                                <div class="mb-1 title d-none d-sm-block">Harga</div>
                                <div class="text-small description d-none d-md-block">Tambah Harga</div>
                            </a>
                        </li>
                        <li class="nav-item d-none" role="presentation">
                            <a class="nav-link text-center" href="#basicLast" role="tab"></a>
                        </li>
                    </ul>
                </div>
                <div class="card-body h-auto">
                    <div class="tab-content">
                        <div class="tab-pane fade h-auto" id="data_konsumen" role="tabpanel">
                            {{-- <h5 class="card-title">Pilih Penjual terlebih dahulu</h5>
                                <p class="card-text text-alternate mb-4">With supporting text below as a natural lead-in to additional content.</p> --}}
                            <form action="/pembelian" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="form-floating col-md-12">
                                        <input type="hidden" value="{{ old('oldKTP') }}" name="oldKTP" id="oldKTP">
                                    </div>
                                    <div class="form-floating mb-3 w-100 col-md-12">
                                        <select class="select-floating @error('penjual') is-invalid @enderror penjual" value="{{ old('penjual') }}" name="penjual" id="penjual">
                                            <option value="">Pilih Penjual</option>
                                            <option value="INDIVIDU">INDIVIDU</option>
                                            <option value="DEALER">NON INDIVIDU</option>
                                        </select>
                                        @error('penjual')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                        <label class="text-label" for="penjual">&nbsp;&nbsp;Pilih Penjual<span class="text-danger"> *</label></span>
                                    </div>
                                    <div id="consumer-content-individu" class="d-none">
                                        <div class="row">
                                            <div class="form-floating mb-3 col-md-6">
                                                <input type="text" name="nik" id="nik" class="form-control @error('nik')is-invalid @enderror" value="{{ old('nik') }}" placeholder="Masukan NIK">
                                                @error('nik')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                                <label class="text-label" for="nik">&nbsp;&nbsp;NIK<span class="text-danger"> *</span></label>
                                            </div>
                                            <div class="form-floating mb-3 col-md-6">
                                                <input type="text" name="nama" id="nama" class="form-control @error('nama')is-invalid @enderror" value="{{ old('nama') }}" placeholder="Masukan Nama Lengkap">
                                                @error('nama')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                                <label class="text-label" for="nama">&nbsp;&nbsp;Nama<span class="text-danger"> *</span></label>
                                            </div>
                                            <div class="form-floating mb-3 col-md-6">
                                                <input type="text" class="form-control @error('no_telepon')is-invalid @enderror" name="no_telepon" id="no_telepon" value="{{ old('no_telepon') }}" placeholder="Masukan Nomor Telepon">
                                                @error('no_telepon')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                                <label class="text-label" for="no_telepon">&nbsp;&nbsp;No Telepon<span class="text-danger"> *</span></label>
                                            </div>
                                            <div class="form-floating mb-3 col-md-6">
                                                <textarea class="form-control @error('alamat')is-invalid @enderror" rows="3" name="alamat" id="alamat" placeholder="Masukan Alamat">{{ old('alamat') }}</textarea>
                                                @error('alamat')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                                <label for="alamat">&nbsp;&nbsp;Alamat<span class="text-danger"> *</span></label>
                                            </div>
                                            <div class="input-group mb-3 col-md-12">
                                                <input type="file" class="form-control @error('photo_ktp')is-invalid @enderror" value="{{ old('photo_ktp') }}" name="photo_ktp" name="photo_ktp" id="photo_ktp" onchange="previewImageKTP()">
                                                <label class="input-group-text" for="inputGroupFile02">Upload Foto KTP</label>
                                                @error('photo_ktp')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 text-center">
                                                <img src="/storage/{{ old('oldKTP', '/ktp/default.png') }}" class="img-fluid sw-20 image-ktp" alt="Foto KTP" />
                                            </div>
                                        </div>
                                    </div>
                                    <div id="consumer-content-dealer" class="d-none">
                                        <div class="row">
                                            <div class="form-floating mb-3 col-md-6">
                                                <input type="text" name="nama_kang" id="nama_kang" class="form-control @error('nama_kang')is-invalid @enderror" value="{{ old('nama_kang') }}" placeholder="Masukan Nama Petugas">
                                                @error('nama_kang')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                                <label class="text-label" for="nama_kang">&nbsp;&nbsp;Nama Petugas<span class="text-danger"> *</span></label>
                                            </div>
                                            <div class="form-floating mb-3 col-md-6">
                                                <input type="text" name="dealer" id="dealer" class="form-control @error('dealer')is-invalid @enderror" value="{{ old('dealer') }}" placeholder="Masukan Nama Dealer">
                                                @error('dealer')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                                <label class="text-label" for="dealer">&nbsp;&nbsp;Nama Dealer<span class="text-danger"> *</span></label>
                                            </div>
                                            {{-- <div class="form-floating mb-3 col-md-6">
                                                <input type="text" name="no_telepon" id="no_telepon_dealer" class="form-control @error('no_telepon')is-invalid @enderror" value="{{ old('no_telepon') }}" placeholder="Masukan No Telepon Dealer">
                                            @error('no_telepon')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <label class="text-label" for="no_telepon">&nbsp;&nbsp;No Telepon Dealer<span class="text-danger"> *</span></label>
                                        </div> --}}
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="tab-pane fade h-auto" id="data_motor" role="tabpanel">
                        {{-- <h5 class="card-title">Second Title</h5>
                                         <p class="card-text text-alternate mb-4">With supporting text below as a natural lead-in to additional content.</p> --}}
                        <div class="row">
                            <div class="form-floating col-md-12">
                                <input type="hidden" value="{{ old('oldKTP') }}" name="oldKTP" id="oldKTP">
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <input type="text" name="merek" id="merek" class="form-control @error('merek')is-invalid @enderror" value="{{ old('merek') }}" placeholder="Masukan Merk">
                                @error('merek')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="merek">&nbsp;&nbsp;Merk<span class="text-danger"> *</label></span>
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <input type="text" name="type" id="type" class="form-control @error('type')is-invalid @enderror" value="{{ old('type') }}" placeholder="Masukan Type/Model">
                                @error('type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="type">&nbsp;&nbsp;Type/Model<span class="text-danger"> *</label></span>
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <input type="text" name="tahun_pembuatan" class="form-control @error('tahun_pembuatan') is-invalid @enderror" placeholder="Masukan Tahun Pembuatan" value="{{ old('tahun_pembuatan') }}">
                                @error('tahun_pembuatan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="tahun_pembuatan">&nbsp;&nbsp;Tahun Pembuatan<span class="text-danger"> *</label></span>
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <input type="text" name="warna" id="warna" class="form-control @error('warna')is-invalid @enderror" value="{{ old('warna') }}" placeholder="Masukan Warna">
                                @error('warna')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="warna">&nbsp;&nbsp;Warna<span class="text-danger"> *</label></span>
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <input type="text" name="no_rangka" id="no_rangka" class="form-control @error('no_rangka')is-invalid @enderror" autocomplete="off" value="{{ old('no_rangka') }}" placeholder="Masukan No Rangka">
                                @error('no_rangka')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="no_rangka">&nbsp;&nbsp;No Rangka<span class="text-danger"> *</label></span>
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <input type="text" name="no_mesin" id="no_mesin" class="form-control @error('no_mesin')is-invalid @enderror" autocomplete="off" value="{{ old('no_mesin') }}" placeholder="Masukan No Rangka">
                                @error('no_mesin')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="no_mesin">&nbsp;&nbsp;No Mesin<span class="text-danger"> *</label></span>
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <input type="text" name="no_polisi" id="no_polisi_input" class="form-control @error('no_polisi')is-invalid @enderror" autocomplete="off" value="{{ old('no_polisi') }}" placeholder="Masukan No Polisi">
                                @error('no_polisi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="no_polisi">&nbsp;&nbsp;No Polisi<span class="text-danger"> *</label></span>
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <input type="text" name="bpkb" id="bpkb" class="form-control @error('bpkb')is-invalid @enderror" value="{{ old('bpkb') }}" placeholder="Masukan BPKB">
                                @error('bpkb')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="bpkb">&nbsp;&nbsp;No BPKB<span class="text-danger"> *</label></span>
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <input type="text" name="nama_bpkb" id="nama_bpkb" class="form-control @error('nama_bpkb')is-invalid @enderror" value="{{ old('nama_bpkb') }}" placeholder="Masukan BPKB">
                                @error('nama_bpkb')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="nama_bpkb">&nbsp;&nbsp;Nama BPKB<span class="text-danger"> *</label></span>
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <input type="text" name="alamat_bpkb" id="alamat_bpkb" class="form-control @error('alamat_bpkb')is-invalid @enderror" value="{{ old('alamat_bpkb') }}" placeholder="Masukan BPKB">
                                @error('alamat_bpkb')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="bpkb">&nbsp;&nbsp;Alamat BPKB<span class="text-danger"> *</label></span>
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <input type="text" class="date-picker form-control @error('berlaku_sampai')is-invalid @enderror" value="{{ old('berlaku_sampai') }}" placeholder="Masukan Masa Berlaku" name="berlaku_sampai" id="berlaku_sampai">
                                @error('berlaku_sampai')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="berlaku_sampai">&nbsp;&nbsp;Berlaku Sampai<span class="text-danger"> *</label></span>
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <input type="text" class="date-picker form-control @error('perpanjang_stnk')is-invalid @enderror" value="{{ old('perpanjang_stnk') }}" placeholder="Masukan Perpanjang STNK" name="perpanjang_stnk" id="perpanjang_stnk">
                                @error('perpanjang_stnk')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="perpanjang_stnk">&nbsp;&nbsp;Perpanjang STNK<span class="text-danger"> *</label></span>
                            </div>
                            <div class="form-floating mb-3 col-md-6">
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control @error('photo_stnk')is-invalid @enderror" value="{{ old('photo_stnk') }}" name="photo_stnk" id="photo_stnk" onchange="previewImageSTNK()">
                                    <label class="input-group-text" for="photo_stnk">Upload Foto STNK</label>
                                    @error('photo_stnk')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <img src="" class="img-fluid sw-20 image-stnk">
                            </div>

                            <div class="form-floating mb-3 col-md-6">
                                <div class="input-group mb-3">
                                    <input type="file" class="form-control @error('photo_bpkb')is-invalid @enderror" value="{{ old('photo_bpkb') }}" name="photo_bpkb" name="photo_bpkb" id="photo_bpkb" onchange="previewImageBPKB()">
                                    <label class="input-group-text" for="inputGroupFile02">Upload Foto BPKB</label>
                                    @error('photo_bpkb')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <img src="" class="img-fluid sw-20 image-bpkb">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade h-auto" id="harga" role="tabpanel">
                        {{-- <h5 class="card-title">Third Title</h5>
                                <p class="card-text text-alternate mb-4">With supporting text below as a natural lead-in to additional content.</p> --}}
                        <div class="row">
                            <div class="form-floating mb-3 col-6">
                                <input type="text" class="form-control money @error('harga_beli')is-invalid @enderror" value="{{ old('harga_beli') }}" placeholder="Masukan Harga Beli" name="harga_beli" id="harga_beli">
                                <label class="text-label" for="harga_beli">&nbsp;&nbsp;Harga Beli<span class="text-danger"> *</label></span>
                                @error('harga_beli')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-floating mb-3 col-6">
                                <input type="text" class="date-picker form-control @error('tanggal_beli')is-invalid @enderror" value="{{ old('tanggal_beli') }}" placeholder="Masukan Tanggal Pembelian" name="tanggal_beli" id="tanggal_beli">
                                <label class="text-label" for="tanggal_beli">&nbsp;&nbsp;Tanggal Beli<span class="text-danger"> *</label></span>
                                @error('tanggal_beli')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="basicLast" role="tabpanel">
                        <div class="text-center mt-5">
                            <h5 class="card-title">Terima Kasih!</h5>
                            <p class="card-text text-alternate mb-4">Silahkan klik button dibawah untuk menambahkan</p>
                            <button class="btn btn-icon btn-icon-start btn-primary btn-add" type="submit">
                                <i data-acorn-icon="plus"></i>
                                <span>Tambah Pembelian</span>
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <div class="card-footer text-center border-0 pt-1">
                <button class="btn btn-icon btn-icon-start btn-outline-primary btn-prev" type="button">
                    <i data-acorn-icon="chevron-left"></i>
                    <span>Back</span>
                </button>
                <button class="btn btn-icon btn-icon-end btn-outline-primary btn-next" type="button">
                    <span>Next</span>
                    <i data-acorn-icon="chevron-right"></i>
                </button>
            </div>
        </div>
        <!-- Basic End -->
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
{{-- Simple Money Format --}}
<script src="/page-script/simple.money.format.js"></script>
<script src="/page-script/simple.money.format.init.js"></script>
{{-- !Simple Money Format --}}


{{-- Jquery --}}
<script src="/page-script/pembelian.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const flashData = $("#pesan").data("flash");
    if (flashData) {
        Swal.fire({
            icon: 'error'
            , title: 'Oops...'
            , text: flashData
        , })
    }

</script>
@endsection
