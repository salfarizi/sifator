<!-- Modal Tambah Roles-->
<div class="modal fade" id="modal-password" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Password</h5>
                <button type="button" class="btn-close btn-close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="javascript:;">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="unique" id="unique">
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="password" id="password" placeholder="Masukkan Nama Role">
                                <label for="password">Password Baru<span class="text-danger"> *</span></label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Masukkan Nama Role">
                                <label for="password_confirmation">Konfirmasi Password<span class="text-danger"> *</span></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="btn-action">
                    <button class="btn btn-primary" id="save-data" type="button">Ubah Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
