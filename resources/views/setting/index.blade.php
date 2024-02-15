@extends('layout.main')
@section('container')
<link href=" https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<div class="flash-data" data-flash="{{ session('pesan') }}"></div>
<div class="flash-data-error" data-flash="{{ session('pesan2') }}"></div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title data-pelanggan">Detail Setting</h4>
            </div>
            <div class="card-body">
                @if($count == 0)
                <form action="/setting" method="post">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="text" name="nama_toko" id="nama_toko" class="form-control @error('nama_toko')is-invalid @enderror" value="{{ old('nama_toko') }}" placeholder="Masukan Nama Toko">
                                @error('nama_toko')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="nama_toko">Nama Toko<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 form-floating">
                                <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control @error('nama_pemilik')is-invalid @enderror" value="{{ old('nama_pemilik') }}" placeholder="Masukan Nama Pemilik">
                                @error('nama_pemilik')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="nama_pemilik">Nama Pemilik<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 form-floating">
                                <textarea class="form-control @error('alamat_toko')is-invalid @enderror" rows="3" name="alamat_toko" id="alamat_toko" placeholder="Masukan Alamat Nasabah">{{ old('alamat_toko') }}</textarea>
                                @error('alamat_toko')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label for="alamat_toko">Alamat Toko<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 form-floating">
                                <input type="text" name="kota" id="kota" class="form-control @error('kota')is-invalid @enderror" value="{{ old('kota') }}" placeholder="Masukan Nama Kota">
                                @error('kota')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="kota">Kota<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 form-floating">
                                <input type="text" name="kontak" id="kontak" class="form-control @error('kontak')is-invalid @enderror" value="{{ old('kontak') }}" placeholder="Masukan Nama Toko">
                                @error('kontak')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="kontak">Informasi Kontak</label>
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button class="btn btn-primary me-md-2" type="submit">Tambah Data Setting</button>
                        </div>
                    </div>
                </form>
                @else
                <form action="/setting/{{ $setting->unique }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="text" name="nama_toko" id="nama_toko" class="form-control @error('nama_toko')is-invalid @enderror" value="{{ old('nama_toko', $setting->nama_toko) }}" placeholder="Masukan Nama Toko">
                                @error('nama_toko')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="nama_toko">Nama Toko<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 form-floating">
                                <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control @error('nama_pemilik')is-invalid @enderror" value="{{ old('nama_pemilik', $setting->nama_pemilik) }}" placeholder="Masukan Nama Pemilik">
                                @error('nama_pemilik')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="nama_pemilik">Nama Pemilik<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3 form-floating">
                                    <textarea class="form-control @error('alamat_toko')is-invalid @enderror" rows="3" name="alamat_toko" id="alamat_toko" placeholder="Masukan Alamat Toko">{{ old('alamat_toko', $setting->alamat_toko) }}</textarea>
                                    @error('alamat_toko')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    <label for="alamat_toko">Alamat Toko<span class="text-danger"> *</span></label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3 form-floating">
                                    <input type="text" name="kota" id="kota" class="form-control @error('kota')is-invalid @enderror" value="{{ old('kota', $setting->kota) }}" placeholder="Masukan Nama Kota">
                                    @error('kota')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    <label class="text-label" for="kota">Kota<span class="text-danger"> *</span></label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3 form-floating">
                                    <input type="text" name="kontak" id="kontak" class="form-control @error('kontak')is-invalid @enderror" value="{{ old('kontak', $setting->kontak) }}" placeholder="Masukan Informasi Kontak">
                                    @error('kontak')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    <label class="text-label" for="kontak">Informasi Kontak<span class="text-danger"> *</span></label>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button class="btn btn-primary me-md-2" type="submit">Edit Data Setting</button>
                            </div>
                        </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Modal Cropper -->
<div class="modal fade" id="modal-cropper" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Cropper</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <img id="image" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary crop-logo-full">Crop</button>
            </div>
        </div>
    </div>
</div>
<script src="/page-script/logo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
</script>
<script>
    const flashData = $('.flash-data').data('flash');
    const flashData2 = $('.flash-data-error').data('flash');
    if (flashData) {
        Swal.fire(
            'Good job!', flashData, 'success'
        )
    }
    if (flashData2) {
        Swal.fire(
            'Error', flashData2, 'error'
        )
    }

</script>
@endsection
