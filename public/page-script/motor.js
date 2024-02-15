$(document).ready(function () {
    let table = $("#dataTables").DataTable({
        buttons: [],
        processing: true,
        responsive: true,
        searching: true,
        bLengthChange: true,
        info: false,
        ordering: true,
        serverSide: true,
        ajax: "/dataTablesReady",
        order: [], // Clearing default order
        dom: '<"row"<"col-sm-12"<"table-container"<"card-body half-padding"f><"card"<"card-body half-padding"t>>>>><"row"<"col-12 mt-3"p>>', // Hiding all other dom elements except table and pagination
        pageLength: 15,
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#dataTables").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "no_polisi",
            },
            {
                data: "merek",
            },
            {
                data: "warna",
            },
            {
                data: "tahun_pembuatan",
            },
            {
                data: "harga_beli",
            },
            {
                data: "status",
                orderable: true,
                searchable: true,
            },
            {
                data: "action",
                orderable: true,
                searchable: true,
            },
        ],
        columnDefs: [
            {
                targets: [7], // index kolom atau sel yang ingin diatur
                className: "text-center", // kelas CSS untuk memposisikan isi ke tengah
            },
            {
                searchable: false,
                orderable: false,
                targets: 0, // Kolom nomor, dimulai dari 0
            },
        ],
        order: [[0, "asc"]],
        language: {
            paginate: {
                previous: '<i class="cs-chevron-left"></i>',
                next: '<i class="cs-chevron-right"></i>',
            },
        },
    });

    let table2 = $("#dataTables2").DataTable({
        processing: true,
        responsive: true,
        searching: true,
        bLengthChange: true,
        info: false,
        serverSide: true,
        ajax: "/dataTablesTerjual",
        order: [], // Clearing default order
        dom: '<"row"<"col-sm-12"<"table-container"<"card-body half-padding"f><"card"<"card-body half-padding"t>>>>><"row"<"col-12 mt-3"p>>', // Hiding all other dom elements except table and pagination
        pageLength: 15,
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#dataTables2").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "no_polisi",
            },
            {
                data: "merek",
            },
            {
                data: "warna",
            },
            {
                data: "tahun_pembuatan",
            },
            {
                data: "harga_jual",
            },
            {
                data: "status",
                orderable: true,
                searchable: true,
            },
            {
                data: "action",
                orderable: true,
                searchable: true,
            },
        ],
        columnDefs: [
            {
                targets: [7], // index kolom atau sel yang ingin diatur
                className: "text-center", // kelas CSS untuk memposisikan isi ke tengah
            },
            {
                searchable: false,
                orderable: false,
                targets: 0, // Kolom nomor, dimulai dari 0
            },
        ],
        order: [[0, "asc"]],
        language: {
            paginate: {
                previous: '<i class="cs-chevron-left"></i>',
                next: '<i class="cs-chevron-right"></i>',
            },
        },
    });

    $("#dataTables").on("click", ".info-motor-button", function () {
        let id = $(this).attr("data-id");
        $.ajax({
            data: {
                id: id,
            },
            url: "/getDataMotor",
            type: "get",
            dataType: "json",
            success: function (response) {
                $("#modal-detail-motor").modal("show");
                $("#bordered_collapseTwo").slideDown();
                $("#no-polisi").html(response.success.no_polisi);
                $("#merk").html(response.success.merek);
                $("#tipe").html(response.success.type);
                $("#warna").html(response.success.warna);
                $("#tahun-pembuatan").html(response.success.tahun_pembuatan);
                $("#no-rangka").html(response.success.no_rangka);
                $("#no-mesin").html(response.success.no_mesin);
                $("#bpkb").html(response.success.bpkb);
                $("#nama-bpkb").html(response.success.nama_bpkb);
                $("#alamat-bpkb").html(response.success.alamat_bpkb);
                $("#berlaku-sampai").html(response.success.berlaku_sampai);
                $("#perpanjang-stnk").html(response.success.perpanjang_stnk);
                $("#foto-bpkb").html(
                    '<button data-img="/storage/' +
                        response.success.photo_bpkb +
                        '" class="btn btn-sm btn-primary rounded text-white look-img-bpkb">Lihat Gambar</button>'
                );
                $("#foto-stnk").html(
                    '<button data-img="/storage/' +
                        response.success.photo_stnk +
                        '" class="btn btn-sm btn-primary rounded text-white look-img-stnk">Lihat Gambar</button>'
                );
            },
        });
    });
    $("#dataTables2").on("click", ".info-motor-button", function () {
        let id = $(this).attr("data-id");
        $.ajax({
            data: {
                id: id,
            },
            url: "/getDataMotor",
            type: "get",
            dataType: "json",
            success: function (response) {
                $("#modal-detail-motor").modal("show");
                $("#bordered_collapseTwo").slideDown();
                $("#no-polisi").html(response.success.no_polisi);
                $("#merk").html(response.success.merek);
                $("#tipe").html(response.success.type);
                $("#warna").html(response.success.warna);
                $("#tahun-pembuatan").html(response.success.tahun_pembuatan);
                $("#no-rangka").html(response.success.no_rangka);
                $("#no-mesin").html(response.success.no_mesin);
                $("#bpkb").html(response.success.bpkb);
                $("#nama-bpkb").html(response.success.nama_bpkb);
                $("#alamat-bpkb").html(response.success.alamat_bpkb);
                $("#berlaku-sampai").html(response.success.berlaku_sampai);
                $("#perpanjang-stnk").html(response.success.perpanjang_stnk);
                $("#foto-bpkb").html(
                    '<button data-img="/storage/' +
                        response.success.photo_bpkb +
                        '" class="btn btn-sm btn-primary rounded text-white look-img-bpkb">Lihat Gambar</button>'
                );
                $("#foto-stnk").html(
                    '<button data-img="/storage/' +
                        response.success.photo_stnk +
                        '" class="btn btn-sm btn-primary rounded text-white look-img-stnk">Lihat Gambar</button>'
                );
            },
        });
    });

    $(".accordion__header").on("click", function () {
        $("#bordered_collapseTwo").slideToggle();
    });

    $("#modal-detail-motor").on("click", ".look-img-stnk", function () {
        let image = $(this).attr("data-img");
        // alert(image);
        $("#img-photo").html(
            '<img src="' +
                image +
                '" alt="" class="img-fluid" style="width: 800px">'
        );
        $("#judul-modal-photo").html("Photo STNK");
        $("#modal-image").modal("show");
    });

    $("#modal-detail-motor").on("click", ".look-img-bpkb", function () {
        let image = $(this).attr("data-img");
        // alert(image);
        $("#img-photo").html(
            '<img src="' +
                image +
                '" alt="" class="img-fluid" style="width: 800px">'
        );
        $("#judul-modal-photo").html("Photo BPKB");
        $("#modal-image").modal("show");
    });

    $(".tab-terjual").on("click", function () {
        document.getElementById("dataTables2").style.width = "70vw";
    });

    // MAINTENANCE
    $("#dataTables").on("click", ".perbaikan-motor-button", function () {
        let id = $(this).attr("data-id");
        let btn_cencel =
            '<button type="button" class="btn btn-rounded btn-danger btn-tutup-maintenance" data-dismiss="modal"><span class="btn-icon-left text-danger"><i class="fa fa-close color-danger"></i></span>Tutup</button>';
        let btn_save =
            '<button type="button" class="btn btn-rounded btn-primary" id="add-data"><span class="btn-icon-left text-primary"><i class="fa fa-plus color-primary"></i></span>Tambah</button>';
        $("#modal-perbaikan-motor").modal("show");
        $("#modal-perbaikan-motor .modal-footer").html(btn_cencel + btn_save);
        $.ajax({
            data: {
                id: id,
            },
            url: "/getDataMotor",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $("#no_polisi_maintenance").val(response.success.no_polisi);
                $("#merek").val(response.success.merek);
                $("#harga_beli").val(
                    new Intl.NumberFormat("id-ID", {
                        style: "currency",
                        currency: "IDR",
                        minimumFractionDigits: 0,
                    })
                        .format(response.success.harga_beli)
                        .replace("Rp", "")
                        .replace(/\./g, ",")
                );
            },
        });

        document.getElementById("dataTablesMaintenance").style.width = "54vw";
        $("#bike_id").val(id);
        table3.ajax.reload();
    });

    $("#biaya").on("keyup", function () {
        $("input.money").simpleMoneyFormat({
            currencySymbol: "Rp",
            decimalPlaces: 0,
            thousandsSeparator: ".",
        });
    });

    //Reset Modal Maintenance
    $(".btn-close-maintenance").on("click", function () {
        $("#jenis_perbaikan").val("");
        $("#tanggal_perbaikan").val("");
        $("#biaya").val("");
        $("#current_unique").val("");
        $(".method").html("");
    });

    $("#modal-perbaikan-motor").on(
        "click",
        ".btn-tutup-maintenance",
        function () {
            $("#modal-perbaikan-motor").modal('hide');
            $("#jenis_perbaikan").val("");
            $("#tanggal_perbaikan").val("");
            $("#biaya").val("");
            $("#current_unique").val("");
            $(".method").html("");
        }
    );

    let table3 = $("#dataTablesMaintenance").DataTable({
        processing: true,
        responsive: true,
        searching: true,
        bLengthChange: true,
        info: false,
        ordering: true,
        serverSide: true,
        dom: '<"row"<"col-sm-12"<"table-container"<"card-body half-padding"f><"card"<"card-body half-padding"t>>>>><"row"<"col-12 mt-3"p>>', // Hiding all other dom elements except table and pagination
        pageLength: 15,
        ajax: {
            url: "/dataTablesMaintenance",
            type: "GET",
            data: function (d) {
                d.id = $("#bike_id").val();
            },
        },
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#dataTablesMaintenance").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "tanggal_perbaikan",
            },
            {
                data: "jenis_perbaikan",
            },
            {
                data: "biaya",
            },
            {
                data: "action",
                orderable: false,
                searchable: false,
            },
        ],
        columnDefs: [
            {
                targets: [4], // index kolom atau sel yang ingin diatur
                className: "text-center", // kelas CSS untuk memposisikan isi ke tengah
            },
            {
                searchable: false,
                orderable: false,
                targets: 0, // Kolom nomor, dimulai dari 0
            },
        ],
        order: [[0, "asc"]],
        language: {
            paginate: {
                previous: '<i class="cs-chevron-left"></i>',
                next: '<i class="cs-chevron-right"></i>',
            },
        },
    });

    $("#modal-perbaikan-motor").on("click", "#add-data", function () {
        let formdata = $("#modal-perbaikan-motor form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-perbaikan-motor form").serialize(),
            url: "/maintenance",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    displayErrors(response.errors);
                } else {
                    table.ajax.reload();
                    table3.ajax.reload();
                    document.getElementById(
                        "dataTablesMaintenance"
                    ).style.width = "54vw";
                    $("#jenis_perbaikan").val("");
                    $("#tanggal_perbaikan").val("");
                    $("#biaya").val("");
                    $("#harga_beli").val(
                        new Intl.NumberFormat("id-ID", {
                            style: "currency",
                            currency: "IDR",
                            minimumFractionDigits: 0,
                        })
                            .format(response.refresh.harga_beli)
                            .replace("Rp", "")
                            .replace(/\./g, ",")
                    );
                    Swal.fire("Good job!", response.success, "success");
                }
            },
        });
    });

    //Ambil Data Maintenance Motor
    $("#dataTablesMaintenance").on(
        "click",
        ".edit-maintenance-button",
        function () {
            let btn_cencel =
                '<button type="button" class="btn btn-rounded btn-warning btn-cencel-update"><span class="btn-icon-left text-warning"><i class="fa fa-close color-warning"></i></span><span class="text-white">Batal Edit</span></button>';
            let btn_update =
                '<button type="button" class="btn btn-rounded btn-primary" id="update-data"><span class="btn-icon-left text-primary"><i class="fa fa-pencil color-primary"></i></span>Ubah</button>';
            $(".method").html(
                '<input type="hidden" name="_method" value="PUT">'
            );
            $("#modal-perbaikan-motor .modal-footer").html(
                btn_cencel + btn_update
            );
            let unique = $(this).attr("data-unique");
            NProgress.start();
            $.ajax({
                data: {
                    unique: unique,
                },
                url: "/getDataMaintenance",
                type: "GET",
                dataType: "json",
                success: function (response) {
                    $("#jenis_perbaikan").val(response.success.jenis_perbaikan);
                    $("#tanggal_perbaikan").val(
                        response.success.tanggal_perbaikan
                    );
                    $("#biaya").val(
                        new Intl.NumberFormat("id-ID", {
                            style: "currency",
                            currency: "IDR",
                            minimumFractionDigits: 0,
                        })
                            .format(response.success.biaya)
                            .replace("Rp", "")
                            .replace(/\./g, ",")
                    );
                    $("#current_unique").val(response.success.unique);
                    NProgress.done();
                },
            });
        }
    );
    //Reset Element Ketika edit dibatalkan
    $("#modal-perbaikan-motor").on("click", ".btn-cencel-update", function () {
        let btn_cencel =
            '<button type="button" class="btn btn-rounded btn-danger btn-tutup-maintenance" data-dismiss="modal"><span class="btn-icon-left text-danger"><i class="fa fa-close color-danger"></i></span>Tutup</button>';
        let btn_save =
            '<button type="button" class="btn btn-rounded btn-primary" id="add-data"><span class="btn-icon-left text-primary"><i class="fa fa-plus color-primary"></i></span>Tambah</button>';
        $("#modal-perbaikan-motor .modal-footer").html(btn_cencel + btn_save);
        $("#jenis_perbaikan").val("");
        $("#tanggal_perbaikan").val("");
        $("#biaya").val("");
        $("#current_unique").val("");
        $(".method").html("");
    });

    //ACTION UPDATE MAINTENANCE
    $("#modal-perbaikan-motor").on("click", "#update-data", function () {
        NProgress.start();
        let formdata = $("#modal-perbaikan-motor form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-perbaikan-motor form").serialize(),
            url: "/maintenance/" + $("#current_unique").val(),
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    displayErrors(response.errors);
                } else {
                    table.ajax.reload();
                    table3.ajax.reload();
                    let btn_cencel =
                        '<button type="button" class="btn btn-rounded btn-danger btn-tutup-maintenance" data-dismiss="modal"><span class="btn-icon-left text-danger"><i class="fa fa-close color-danger"></i></span>Tutup</button>';
                    let btn_save =
                        '<button type="button" class="btn btn-rounded btn-primary" id="add-data"><span class="btn-icon-left text-primary"><i class="fa fa-plus color-primary"></i></span>Tambah</button>';
                    $("#modal-perbaikan-motor .modal-footer").html(
                        btn_cencel + btn_save
                    );
                    $("#jenis_perbaikan").val("");
                    $("#tanggal_perbaikan").val("");
                    $("#biaya").val("");
                    $("#current_unique").val("");
                    $("#harga_beli").val(
                        new Intl.NumberFormat("id-ID", {
                            style: "currency",
                            currency: "IDR",
                            minimumFractionDigits: 0,
                        })
                            .format(response.refresh.harga_beli)
                            .replace("Rp", "")
                            .replace(/\./g, ",")
                    );
                    $(".method").html("");
                    Swal.fire("Good job!", response.success, "success");
                    NProgress.done();
                }
            },
        });
    });

    // ACTION HAPUS MAINTENANCE
    $("#dataTablesMaintenance").on(
        "click",
        ".delete-maintenance-button",
        function () {
            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Data maintenance tidak akan bisa dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    let unique = $(this).attr("data-unique");
                    let token = $(this).attr("data-token");
                    $.ajax({
                        data: {
                            unique: unique,
                            _method: "DELETE",
                            _token: token,
                        },
                        url: "/maintenance/" + unique,
                        type: "POST",
                        dataType: "json",
                        success: function (response) {
                            table.ajax.reload();
                            table3.ajax.reload();
                            let btn_cencel =
                                '<button type="button" class="btn btn-rounded btn-danger btn-tutup-maintenance" data-dismiss="modal"><span class="btn-icon-left text-danger"><i class="fa fa-close color-danger"></i></span>Tutup</button>';
                            let btn_save =
                                '<button type="button" class="btn btn-rounded btn-primary" id="add-data"><span class="btn-icon-left text-primary"><i class="fa fa-plus color-primary"></i></span>Tambah</button>';
                            $("#modal-perbaikan-motor .modal-footer").html(
                                btn_cencel + btn_save
                            );
                            $("#jenis_perbaikan").val("");
                            $("#tanggal_perbaikan").val("");
                            $("#biaya").val("");
                            $("#current_unique").val("");
                            $(".method").html("");
                            $("#harga_beli").val(
                                new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR",
                                    minimumFractionDigits: 0,
                                })
                                    .format(response.refresh.harga_beli)
                                    .replace("Rp", "")
                                    .replace(/\./g, ",")
                            );
                            Swal.fire("Deleted!", response.success, "success");
                        },
                    });
                }
            });
        }
    );

    //Hendler Error
    function displayErrors(errors) {
        // menghapus class 'is-invalid' dan pesan error sebelumnya
        $("input.form-control").removeClass("is-invalid");
        $("select.form-control").removeClass("is-invalid");
        $("div.invalid-feedback").remove();

        // menampilkan pesan error baru
        $.each(errors, function (field, messages) {
            let inputElement = $("input[name=" + field + "]");
            let selectElement = $("select[name=" + field + "]");
            let feedbackElement = $(
                '<div class="invalid-feedback ml-2"></div>'
            );

            $.each(messages, function (index, message) {
                feedbackElement.append(
                    $('<p class="p-0 m-0">' + message + "</p>")
                );
            });

            if (inputElement.length > 0) {
                inputElement.addClass("is-invalid");
                inputElement.after(feedbackElement);
            }

            if (selectElement.length > 0) {
                selectElement.addClass("is-invalid");
                selectElement.after(feedbackElement);
            }
            inputElement.each(function () {
                if (inputElement.attr("type") == "text") {
                    inputElement.on("click", function () {
                        $(this).removeClass("is-invalid");
                    });
                    inputElement.on("change", function () {
                        $(this).removeClass("is-invalid");
                    });
                } else if (inputElement.attr("type") == "date") {
                    inputElement.on("change", function () {
                        $(this).removeClass("is-invalid");
                    });
                }
            });
        });
    }
    let monthBefore = $(".dtp-select-month-before .material-icons");
    monthBefore.addClass("fa fa-arrow-left fa-lg text-white");
    monthBefore.removeClass("material-icons");
    monthBefore.html("");

    let yearBefore = $(".dtp-select-year-before .material-icons");
    yearBefore.addClass("fa fa-arrow-left fa-lg text-white");
    yearBefore.removeClass("material-icons");
    yearBefore.html("");

    let monthAfter = $(".dtp-select-month-after .material-icons");
    monthAfter.addClass("fa fa-arrow-right fa-lg text-white");
    monthAfter.removeClass("material-icons");
    monthAfter.html("");

    let yearAfter = $(".dtp-select-year-after .material-icons");
    yearAfter.addClass("fa fa-arrow-right fa-lg text-white");
    yearAfter.removeClass("material-icons");
    yearAfter.html("");

    let yearRangeBefore = $(".dtp-select-year-range.before .material-icons");
    yearRangeBefore.addClass("fa fa-arrow-up fa-lg text-dark");
    yearRangeBefore.removeClass("material-icons");
    yearRangeBefore.html("");

    let yearRangeAfter = $(".dtp-select-year-range.after .material-icons");
    yearRangeAfter.addClass("fa fa-arrow-down fa-lg text-dark");
    yearRangeAfter.removeClass("material-icons");
    yearRangeAfter.html("");
});
