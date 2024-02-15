@extends('layout.main')
@section('container')
<link href=" https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<div class="flash-data" data-flash="{{ session('pesan') }}"></div>
<div class="flash-data-error" data-flash="{{ session('pesan2') }}"></div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title data-pelanggan">Detail Profile</h4>
            </div>
            <div class="card-body">
                <form action="/updateProfile" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="oldPhoto" value="{{ auth()->user()->photo }}">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="mb-3 form-floating">
                                <input type="text" name="name" id="name" class="form-control @error('name')is-invalid @enderror" value="{{ old('name', auth()->user()->name) }}" placeholder="Masukan Username">
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="name">Nama<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 form-floating">
                                <input type="email" name="email" id="email" class="form-control @error('email')is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}" placeholder="Masukan Email">
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label for="email">Email<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 form-floating">
                                <input type="password" name="new_password" id="new_password" class="form-control @error('new_password')is-invalid @enderror" placeholder="Masukan Password Baru">
                                <a class="position-absolute t-3 e-3">
                                    <span class="bi-eye text-black" id="eye2" aria-hidden="true"></span>
                                    <span class="bi-eye-slash text-primary d-none" id="eye-slash2" aria-hidden="true"></span>
                                </a>
                                @error('new_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                                <label class="text-label" for="new_password">Password Baru</label>
                            </div>
                            <span class="text-success m-0">*Password Baru boleh dikosongkan</span>
                        </div>
                        <div class="input-group mb-3">
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" name="photo">
                            <label class="input-group-text" style="display:block" for="inputGroupFile02">Upload</label>
                            @error('photo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <span class="text-success m-0">*Photo boleh dikosongkan</span>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button class="btn btn-primary me-md-2 button-conf" type="button">Edit Data Profile</button>
                        </div>
                    </div>
                    {{-- Modal Konfirmasi Password --}}
                    <div class="modal fade" id="conf_password" tabindex="-1" aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title h4" id="exampleModalFullscreenLabel">Masukan Password</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Masukan Password Lama" required>
                                        <a class="position-absolute t-3 e-3">
                                            <span class="bi-eye text-black" id="eye" aria-hidden="true"></span>
                                            <span class="bi-eye-slash text-primary d-none" id="eye-slash" aria-hidden="true"></span>
                                        </a>
                                        <label for="password">Masukan Password Lama <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-secondary btn-kwitansi">Update Profile</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal  Cropper -->
<div class="modal fade" id="modal-cropper" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <img id="image" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary crop_pria">Crop</button>
            </div>
        </div>
    </div>
</div>
<script src="/page-script/photo-profile.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
<script>
    $(document).ready(function() {
        $('#eye').on("click", function() {
            $('#eye').toggleClass("d-none");
            $('#eye-slash').toggleClass("d-none");
            $('#password').attr('type', 'text');

        });
        $('#eye-slash').on("click", function() {
            $('#eye').toggleClass("d-none");
            $('#eye-slash').toggleClass("d-none");
            $('#password').attr('type', 'password');
        })

        $('#eye2').on("click", function() {
            $('#eye2').toggleClass("d-none");
            $('#eye-slash2').toggleClass("d-none");
            $('#new_password').attr('type', 'text');
        });
        $('#eye-slash2').on("click", function() {
            $('#eye2').toggleClass("d-none");
            $('#eye-slash2').toggleClass("d-none");
            $('#new_password').attr('type', 'password');
        });
    });

</script>
@endsection
