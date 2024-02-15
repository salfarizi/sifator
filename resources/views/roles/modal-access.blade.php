<!-- Modal Tambah Roles-->
<div class="modal fade" id="modal-access" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hak Access</h5>
                <button type="button" class="btn-close btn-close-access" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="unique" id="unique_access" value="0">
                <table class="table table-hover" id="table-access">
                    <thead>
                        <tr>
                            <td class="text-center">No</td>
                            <td>Access</td>
                            <td class="text-center">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 1;
                        @endphp
                        @foreach ($menus as $menu)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td>{{ $menu->menu }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-icon btn-icon-only btn-secondary" data-menu="{{ $menu->menu }}" id="tambah-access" type="button">
                                    <i class="bi-plus-circle"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-icon-only btn-danger" data-menu="{{ $menu->menu }}" id="hapus-access" type="button">
                                    <i class="bi-dash-circle"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <h5 class="">Akses Yang Diberikan :</h5>
                <button type="button" id="list-access" class="btn btn-primary mt-5">

                </button>
            </div>
        </div>
    </div>
