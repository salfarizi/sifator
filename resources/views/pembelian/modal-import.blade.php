<div class="modal fade" id="modal-exel" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Exel</h5>
                <button type="button" class="btn-close btn-close-access" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/upload-exel" enctype="multipart/form-data" type="post">
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <input type="file" class="form-control" id="upoad-exel" name="upload-exel" required>
                        <label class="input-group-text" for="upoad-exel">Upload</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
