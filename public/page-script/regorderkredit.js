$(document).ready(function () {
    let table = $("#datatableBoxed_reg_order_kredit").DataTable({
        processing: true,
        responsive: true,
        searching: true,
        bLengthChange: true,
        info: false,
        ordering: true,
        serverSide: true,
        ajax: "/datatablesRegOrderKredit",
        dom: '<"row"<"col-sm-12"<"table-container"<"card-body half-padding"f><"card"<"card-body half-padding"t>>>>><"row"<"col-12 mt-3"p>>', // Hiding all other dom elements except table and pagination
        // Hiding all other dom elements except table and pagination
        pageLength: 15,
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#datatableBoxed_reg_order_kredit")
                        .DataTable()
                        .page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "no_reg",
            },
            {
                data: "nama",
            },
            {
                data: "alamat",
            },
            {
                data: "no_telepon",
            },
            {
                data: "action",
            },
        ],
        columnDefs: [
            {
                targets: [5], // index kolom atau sel yang ingin diatur
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

    let table2 = $("#datatableBoxed_reg_order_list").DataTable({
        processing: true,
        responsive: true,
        searching: true,
        bLengthChange: true,
        info: false,
        ordering: true,
        serverSide: true,
        ajax: {
            url: "/listPengajualOrder",
            type: "GET",
            data: function (d) {
                d.id = $("#unique_no_reg").val();
            },
        },
        dom: '<"row"<"col-sm-12"<"table-container"<"card-body half-padding"f><"card"<"card-body half-padding"t>>>>><"row"<"col-12 mt-3"p>>', // Hiding all other dom elements except table and pagination
        // Hiding all other dom elements except table and pagination
        pageLength: 15,
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#datatableBoxed_reg_order_kredit")
                        .DataTable()
                        .page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "nama_dealer",
            },
            {
                data: "via",
            },
            {
                data: "merk",
            },
            {
                data: "type",
            },
            {
                data: "status",
                render: function (data, type, row, meta) {
                    if (data == "DALAM PENGAJUAN") {
                        return type === "display"
                            ? '<div class="badge bg-info text-uppercase py-1 px-2">' +
                                  data +
                                  "</div>"
                            : data;
                    } else if (data == "DI SETUJUI") {
                        return type === "display"
                            ? '<div class="badge bg-success text-uppercase py-1 px-2">' +
                                  data +
                                  "</div>"
                            : data;
                    } else if (data == "DI TOLAK") {
                        return type === "display"
                            ? '<div class="badge bg-danger text-uppercase py-1 px-2">' +
                                  data +
                                  "</div>"
                            : data;
                    }
                },
            },
            {
                data: "unique",
                render: function (data, type, row, meta) {
                    return type === "display"
                        ? '<button class="btn btn-rounded btn-sm btn-success button-disetujui" data-bs-toggle="tooltip" data-bs-placement="top" title="Order Disetujui" data-unique="' +
                              data +
                              '"><i class="bi-check-all"></i></button> <button class="btn btn-rounded btn-sm btn-danger button-ditolak" data-bs-toggle="tooltip" data-bs-placement="top" title="Order Ditolak" data-unique="' +
                              data +
                              '"><i class="bi-x-circle"></i></button> <button class="btn btn-rounded btn-sm btn-info button-proses" data-bs-toggle="tooltip" data-bs-placement="top" title="Order Dalam Pengajuan" data-unique="' +
                              data +
                              '"><i class="bi-hourglass-split"></i></button>'
                        : data;
                },
            },
            {
                data: "action",
            },
        ],
        columnDefs: [
            {
                targets: [6], // index kolom atau sel yang ingin diatur
                className: "text-center", // kelas CSS untuk memposisikan isi ke tengah
            },
            {
                targets: [5], // index kolom atau sel yang ingin diatur
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

    //RESET MODAL
    $(".btn-close").on("click", function () {
        $("#nik").val("");
        $("#nama_pembeli").val("");
        $("#alamat").val("");
        $("#nama_pembeli").removeAttr("style");
        $("#alamat").removeAttr("style");
        $("#tempat_lahir").removeAttr("style");
        $("#tanggal_lahir").removeAttr("style");
        $("#no_telepon").removeAttr("style");
        $("#img-ktp img").attr("src", "/storage/ktp/default.png");
        $("#jenis_kelamin").val(null).trigger("change");
        $("#no_telepon").val("");
        $("#tempat_lahir").val("");
        $("#tanggal_lahir").val("");
        $("#photo_ktp").val("");
        $("#photo-ktp").val("");

        $("#nik").removeClass("is-invalid");
        $("#no_telepon").removeClass("is-invalid");
        $("#tempat_lahir").removeClass("is-invalid");
        $("#tanggal_lahir").removeClass("is-invalid");
        $("#photo_ktp").removeClass("is-invalid");
        $("#photo-ktp").removeClass("is-invalid");
        $("#nama_pembeli").removeClass("is-invalid");
        $("#alamat").removeClass("is-invalid");

        $("#modal-pemohon .select2").css({
            border: "0px",
            "border-radius": "10px",
        });
        $(".invalid-jk").html("");
        $("#old_ktp").val("");
    });

    $("#btn-add-data").on("click", function () {
        let element =
            '<button class="btn btn-icon btn-icon-end btn-primary btn-add-data" type="button"><span>Tambah</span></button>';
        $("#btn-action").html(element);
    });

    // JIKA NIK SUDAH ADA
    $("#nik").on("keyup", function () {
        NProgress.start();
        let nik = $(this).val();
        $.ajax({
            data: {
                nik: nik,
            },
            url: "/cekNikPembeli",
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#nama_pembeli").val(response.success.nama);
                    $("#alamat").val(response.success.alamat);
                    $("#no_telepon").val(response.success.no_telepon);
                    if (
                        response.success.tempat_lahir &&
                        response.success.tanggal_lahir
                    ) {
                        $("#tempat_lahir").val(response.success.tempat_lahir);
                        $("#jenis_kelamin")
                            .val(response.success.jenis_kelamin)
                            .trigger("change");
                        $("#tanggal_lahir").val(response.success.tanggal_lahir);
                        $("#tempat_lahir").css({
                            "background-color": "rgba(215, 218, 227, 0.3)",
                        });
                        $("#tanggal_lahir").css({
                            "background-color": "rgba(215, 218, 227, 0.3)",
                        });
                        $("#jenis_kelamin").css({
                            "background-color": "rgba(215, 218, 227, 0.3)",
                        });
                    }
                    $("#nama_pembeli").css({
                        "background-color": "rgba(215, 218, 227, 0.3)",
                    });
                    $("#alamat").css({
                        "background-color": "rgba(215, 218, 227, 0.3)",
                    });
                    $("#no_telepon").css({
                        "background-color": "rgba(215, 218, 227, 0.3)",
                    });
                    $("#nama_pembeli").removeClass("is-invalid");
                    $("#alamat").removeClass("is-invalid");
                    $("#no_telepon").removeClass("is-invalid");
                    $("#tanggal_lahir").removeClass("is-invalid");
                    $("#tempat_lahir").removeClass("is-invalid");
                    if (response.success.photo_ktp == null) {
                        $("#img-ktp img").attr(
                            "src",
                            "/storage/ktp/default.png"
                        );
                    } else {
                        $("#img-ktp img").attr(
                            "src",
                            "/storage/ktp_pembeli/" + response.success.photo_ktp
                        );
                    }
                    $("#old_ktp").val(response.success.photo_ktp);
                    NProgress.done();
                } else {
                    $("#nama_pembeli").val("");
                    $("#alamat").val("");
                    $("#jenis_kelamin").val(null).trigger("change");
                    $("#no_telepon").val("");
                    $("#tempat_lahir").val("");
                    $("#tanggal_lahir").val("");
                    $("#nama_pembeli").removeAttr("style");
                    $("#alamat").removeAttr("style");
                    $("#tempat_lahir").removeAttr("style");
                    $("#tanggal_lahir").removeAttr("style");
                    $("#no_telepon").removeAttr("style");
                    $("#img-ktp img").attr("src", "/storage/ktp/default.png");
                    $("#old_ktp").val("");
                    NProgress.done();
                }
            },
        });
    });

    // ACTION SIMPAN REGISTER ORDER
    $("#btn-action").on("click", ".btn-add-data", function () {
        $("#modal-pemohon .invalid-jk").remove();
        $("#btn-action .btn-add-data").attr("disabled", "true");
        let formdata = $("#modal-pemohon form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-pemohon form").serialize(),
            url: "/regorderkredit",
            type: "POST",
            dataType: "json",
            success: function (response) {
                $("#btn-action .btn-add-data").removeAttr("disabled");
                if (response.errors) {
                    if (response.errors.jenis_kelamin) {
                        $("#modal-pemohon .select2").css({
                            border: "1px solid red",
                            "border-radius": "10px",
                        });
                        $("#modal-pemohon .select2").after(
                            '<small class="text-center text-danger invalid-jk">Jenis kelamin tidak boleh kosong</small>'
                        );
                    }
                    displayErrors(response.errors);
                } else {
                    $("#modal-pemohon").modal("hide");
                    $("#nik").val("");
                    $("#nama_pembeli").val("");
                    $("#alamat").val("");
                    $("#nama_pembeli").removeAttr("style");
                    $("#alamat").removeAttr("style");
                    $("#tempat_lahir").removeAttr("style");
                    $("#tanggal_lahir").removeAttr("style");
                    $("#no_telepon").removeAttr("style");
                    $("#img-ktp img").attr("src", "/storage/ktp/default.png");
                    $("#jenis_kelamin").val(null).trigger("change");
                    $("#no_telepon").val("");
                    $("#tempat_lahir").val("");
                    $("#tanggal_lahir").val("");
                    $("#photo_ktp").val("");
                    $("#photo-ktp").val("");
                    Swal.fire("Good job!", response.success, "success");
                    table.ajax.reload();
                }
            },
        });
    });
    $("#jenis_kelamin").on("change", function () {
        $("#modal-pemohon .select2").css({
            border: "0px",
            "border-radius": "10px",
        });
        $(".invalid-jk").html("");
    });

    //KETIKA INGIN MENAMBAHKAN REGISTER ORDER
    $("#datatableBoxed_reg_order_kredit").on(
        "click",
        ".register-button",
        function () {
            let btn_element =
                '<div> <button class="btn btn-icon btn-icon-end btn-primary" type="button" id="btn-add-list-order"><span>Tambah</span></button></div>';
            $("#btn-action-list-order").html(btn_element);
            let unique = $(this).attr("data-unique");
            $("#modal-regorderkredit").modal("show");
            $("#unique_no_reg").val(unique);
            table2.ajax.reload();
            $.ajax({
                data: { unique: unique },
                url: "/getDataBuyerRegOrder",
                type: "GET",
                dataType: "json",
                success: function (response) {
                    // console.log(response.data);
                    $("#nama_nasabah")
                        .val(response.data.nama)
                        .trigger("change");
                    $("#no_telepon_nasabah").val(response.data.no_telepon);
                    $("#alamat_nasabah").val(response.data.alamat);
                    $("#buyer_id").val(response.data.buyer_id);
                },
            });
        }
    );

    // ACTION SIMPAN LIST ORDER
    $("#btn-action-list-order").on("click", "#btn-add-list-order", function () {
        $("#modal-pemohon .invalid-jt").remove();
        $("#modal-pemohon .invalid-via").remove();
        $("#btn-action-list-order #btn-add-list-order").attr(
            "disabled",
            "true"
        );
        let formdata = $("#modal-regorderkredit form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-regorderkredit form").serialize(),
            url: "/listRegOrder",
            type: "POST",
            dataType: "json",
            success: function (response) {
                // console.log(response);
                $("#btn-action-list-order #btn-add-list-order").removeAttr(
                    "disabled"
                );
                if (response.errors) {
                    if (response.errors.jenis_transaksi) {
                        $("#modal-regorderkredit .select2").eq(0).css({
                            border: "1px solid red",
                            "border-radius": "10px",
                        });
                        $("#modal-regorderkredit .select2")
                            .eq(0)
                            .after(
                                '<small class="text-center text-danger invalid-jt">Jenis kelamin tidak boleh kosong</small>'
                            );
                    }
                    if (response.errors.via) {
                        $("#modal-regorderkredit .select2").eq(1).css({
                            border: "1px solid red",
                            "border-radius": "10px",
                        });
                        $("#modal-regorderkredit .select2")
                            .eq(1)
                            .after(
                                '<small class="text-center text-danger invalid-via">Kredit via leasing tidak boleh kosong</small>'
                            );
                    }
                    displayErrors(response.errors);
                } else {
                    $("#nama_dealer").val("");
                    $("#cmo").val("");
                    $("#pic").val("");
                    $("#jenis_transaksi").val(null).trigger("change");
                    $("#kredit_via_leasing").val(null).trigger("change");
                    $("#merk").val("");
                    $("#type").val("");
                    $("#tahun_pembuatan").val("");
                    $("#otr").val("");
                    $("#dp_po").val("");
                    $("#pencairan").val("");
                    $("#dp").val("");
                    $("#angsuran").val("");
                    $("#tenor").val("");
                    Swal.fire("Good job!", response.success, "success");
                    table2.ajax.reload();
                }
            },
        });
    });

    // AMBIL DATA LIST ORDER YANG AKAN DI UPDATE
    $("#modal-regorderkredit").on(
        "click",
        ".edit-list-order-button",
        function () {
            let btn_element =
                '<div><button class="btn btn-icon btn-icon-end btn-warning" type="button" id="btn-cancel-edit"><span>Batal</span></button></div><div> <button class="btn btn-icon btn-icon-end btn-primary" type="button" id="btn-edit-list-order"><span>Update</span></button></div>';
            $("#btn-action-list-order").html(btn_element);
            let unique = $(this).attr("data-unique");
            $.ajax({
                data: { unique: unique },
                url: "/getDataListOrder",
                type: "GET",
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    $("#current_unique").val(response.success.unique);
                    $("#nama_dealer").val(response.success.nama_dealer);
                    $("#cmo").val(response.success.cmo);
                    $("#pic").val(response.success.pic);
                    $("#jenis_transaksi")
                        .val(response.success.jenis_transaksi)
                        .trigger("change");
                    $("#kredit_via_leasing")
                        .val(response.success.via)
                        .trigger("change");
                    $("#merk").val(response.success.merk);
                    $("#type").val(response.success.type);
                    $("#tahun_pembuatan").val(response.success.tahun_pembuatan);
                    $("#otr").val(response.success.otr);
                    $("#dp_po").val(response.success.dp_po);
                    $("#pencairan").val(response.success.pencairan);
                    $("#dp").val(response.success.dp);
                    $("#angsuran").val(response.success.angsuran);
                    $("#tenor").val(response.success.tenor);

                    $("#nama_dealer").removeClass("is-invalid");
                    $("#cmo").removeClass("is-invalid");
                    $("#pic").removeClass("is-invalid");
                    $("#merk").removeClass("is-invalid");
                    $("#type").removeClass("is-invalid");
                    $("#tahun_pembuatan").removeClass("is-invalid");
                    $("#otr").removeClass("is-invalid");
                    $("#dp_po").removeClass("is-invalid");
                    $("#pencairan").removeClass("is-invalid");
                    $("#dp").removeClass("is-invalid");
                    $("#angsuran").removeClass("is-invalid");
                    $("#tenor").removeClass("is-invalid");

                    $("#nama_dealer").removeAttr("disabled");
                    $("#cmo").removeAttr("disabled");
                    $("#pic").removeAttr("disabled");
                    $("#merk").removeAttr("disabled");
                    $("#type").removeAttr("disabled");
                    $("#tahun_pembuatan").removeAttr("disabled");
                    $("#otr").removeAttr("disabled");
                    $("#dp_po").removeAttr("disabled");
                    $("#pencairan").removeAttr("disabled");
                    $("#dp").removeAttr("disabled");
                    $("#angsuran").removeAttr("disabled");
                    $("#tenor").removeAttr("disabled");

                    $("#select-jenis-transaksi").removeClass("d-none");
                    $("#select-via").removeClass("d-none");
                    $("#view-jenis-transaksi").addClass("d-none");

                    $("#modal-regorderkredit .invalid-jt").remove();
                    $("#modal-regorderkredit .invalid-via").remove();

                    $("#modal-regorderkredit .select2").eq(0).css({
                        border: "0px",
                        "border-radius": "10px",
                    });
                    $("#modal-regorderkredit .select2").eq(1).css({
                        border: "0px",
                        "border-radius": "10px",
                    });
                },
            });
        }
    );
    //LIHAT DATA LIST ORDER
    $("#modal-regorderkredit").on("click", ".info-button-list", function () {
        let btn_element =
            '<div><button class="btn btn-icon btn-icon-end btn-warning" type="button" id="btn-cancel-edit">Batal</button></div>';
        $("#btn-action-list-order").html(btn_element);
        let unique = $(this).attr("data-unique");
        $.ajax({
            data: { unique: unique },
            url: "/getDataListOrder",
            type: "GET",
            dataType: "json",
            success: function (response) {
                // console.log(response);
                $("#current_unique").val(response.success.unique);
                $("#nama_dealer").val(response.success.nama_dealer);
                $("#cmo").val(response.success.cmo);
                $("#pic").val(response.success.pic);
                $("#view_jenis_transaksi").val(
                    response.success.jenis_transaksi
                );
                $("#view_via").val(response.success.via);
                $("#merk").val(response.success.merk);
                $("#type").val(response.success.type);
                $("#tahun_pembuatan").val(response.success.tahun_pembuatan);
                $("#otr").val(response.success.otr);
                $("#dp_po").val(response.success.dp_po);
                $("#pencairan").val(response.success.pencairan);
                $("#dp").val(response.success.dp);
                $("#angsuran").val(response.success.angsuran);
                $("#tenor").val(response.success.tenor);

                $("#nama_dealer").removeClass("is-invalid");
                $("#cmo").removeClass("is-invalid");
                $("#pic").removeClass("is-invalid");
                $("#merk").removeClass("is-invalid");
                $("#type").removeClass("is-invalid");
                $("#tahun_pembuatan").removeClass("is-invalid");
                $("#otr").removeClass("is-invalid");
                $("#dp_po").removeClass("is-invalid");
                $("#pencairan").removeClass("is-invalid");
                $("#dp").removeClass("is-invalid");
                $("#angsuran").removeClass("is-invalid");
                $("#tenor").removeClass("is-invalid");

                //disable semua input
                $("#nama_dealer").attr("disabled", "true");
                $("#cmo").attr("disabled", "true");
                $("#pic").attr("disabled", "true");
                $("#merk").attr("disabled", "true");
                $("#type").attr("disabled", "true");
                $("#tahun_pembuatan").attr("disabled", "true");
                $("#otr").attr("disabled", "true");
                $("#dp_po").attr("disabled", "true");
                $("#pencairan").attr("disabled", "true");
                $("#dp").attr("disabled", "true");
                $("#angsuran").attr("disabled", "true");
                $("#tenor").attr("disabled", "true");

                $("#modal-regorderkredit .invalid-jt").remove();
                $("#modal-regorderkredit .invalid-via").remove();

                $("#modal-regorderkredit .select2").eq(0).css({
                    border: "0px",
                    "border-radius": "10px",
                });
                $("#modal-regorderkredit .select2").eq(1).css({
                    border: "0px",
                    "border-radius": "10px",
                });

                $("#select-jenis-transaksi").addClass("d-none");
                $("#select-via").addClass("d-none");
                $("#view-jenis-transaksi").removeClass("d-none");
            },
        });
    });

    //ACTION UPDATE DATA LIST ORDER
    $("#btn-action-list-order").on(
        "click",
        "#btn-edit-list-order",
        function () {
            $("#modal-regorderkredit .invalid-jt").remove();
            $("#modal-regorderkredit .invalid-via").remove();
            $("#btn-action-list-order #btn-edit-list-order").attr(
                "disabled",
                "true"
            );
            let formdata = $("#modal-regorderkredit form").serializeArray();
            let data = {};
            $(formdata).each(function (index, obj) {
                data[obj.name] = obj.value;
            });
            $.ajax({
                data: $("#modal-regorderkredit form").serialize(),
                url: "/listRegOrderUpdate",
                type: "POST",
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    $("#btn-action-list-order #btn-edit-list-order").removeAttr(
                        "disabled"
                    );
                    if (response.errors) {
                        if (response.errors.jenis_transaksi) {
                            $("#modal-regorderkredit .select2").eq(0).css({
                                border: "1px solid red",
                                "border-radius": "10px",
                            });
                            $("#modal-regorderkredit .select2")
                                .eq(0)
                                .after(
                                    '<small class="text-center text-danger invalid-jt">Jenis kelamin tidak boleh kosong</small>'
                                );
                        }
                        if (response.errors.via) {
                            $("#modal-regorderkredit .select2").eq(1).css({
                                border: "1px solid red",
                                "border-radius": "10px",
                            });
                            $("#modal-regorderkredit .select2")
                                .eq(1)
                                .after(
                                    '<small class="text-center text-danger invalid-via">Kredit via leasing tidak boleh kosong</small>'
                                );
                        }
                        displayErrors(response.errors);
                    } else {
                        let btn_element =
                            '<div> <button class="btn btn-icon btn-icon-end btn-primary" type="button" id="btn-add-list-order"><span>Tambah</span></button></div>';
                        $("#btn-action-list-order").html(btn_element);
                        $("#current_unique").val("");
                        $("#nama_dealer").val("");
                        $("#cmo").val("");
                        $("#pic").val("");
                        $("#jenis_transaksi").val(null).trigger("change");
                        $("#kredit_via_leasing").val(null).trigger("change");
                        $("#merk").val("");
                        $("#type").val("");
                        $("#tahun_pembuatan").val("");
                        $("#otr").val("");
                        $("#dp_po").val("");
                        $("#pencairan").val("");
                        $("#dp").val("");
                        $("#angsuran").val("");
                        $("#tenor").val("");
                        Swal.fire("Good job!", response.success, "success");
                        table2.ajax.reload();
                    }
                },
            });
        }
    );

    // KEtikA TOBOL BATAL UPDATE DI KLIK
    $("#btn-action-list-order").on("click", "#btn-cancel-edit", function () {
        let btn_element =
            '<div> <button class="btn btn-icon btn-icon-end btn-primary" type="button" id="btn-add-list-order"><span>Tambah</span></button></div>';
        $("#btn-action-list-order").html(btn_element);
        $("#current_unique").val("");
        $("#nama_dealer").val("");
        $("#cmo").val("");
        $("#pic").val("");
        $("#jenis_transaksi").val(null).trigger("change");
        $("#kredit_via_leasing").val(null).trigger("change");
        $("#merk").val("");
        $("#type").val("");
        $("#tahun_pembuatan").val("");
        $("#otr").val("");
        $("#dp_po").val("");
        $("#pencairan").val("");
        $("#dp").val("");
        $("#angsuran").val("");
        $("#tenor").val("");

        $("#nama_dealer").removeClass("is-invalid");
        $("#cmo").removeClass("is-invalid");
        $("#pic").removeClass("is-invalid");
        $("#merk").removeClass("is-invalid");
        $("#type").removeClass("is-invalid");
        $("#tahun_pembuatan").removeClass("is-invalid");
        $("#otr").removeClass("is-invalid");
        $("#dp_po").removeClass("is-invalid");
        $("#pencairan").removeClass("is-invalid");
        $("#dp").removeClass("is-invalid");
        $("#angsuran").removeClass("is-invalid");
        $("#tenor").removeClass("is-invalid");

        $("#nama_dealer").removeAttr("disabled");
        $("#cmo").removeAttr("disabled");
        $("#pic").removeAttr("disabled");
        $("#merk").removeAttr("disabled");
        $("#type").removeAttr("disabled");
        $("#tahun_pembuatan").removeAttr("disabled");
        $("#otr").removeAttr("disabled");
        $("#dp_po").removeAttr("disabled");
        $("#pencairan").removeAttr("disabled");
        $("#dp").removeAttr("disabled");
        $("#angsuran").removeAttr("disabled");
        $("#tenor").removeAttr("disabled");

        $("#select-jenis-transaksi").removeClass("d-none");
        $("#select-via").removeClass("d-none");
        $("#view-jenis-transaksi").addClass("d-none");

        $("#modal-regorderkredit .invalid-jt").remove();
        $("#modal-regorderkredit .invalid-via").remove();

        $("#modal-regorderkredit .select2").eq(0).css({
            border: "0px",
            "border-radius": "10px",
        });
        $("#modal-regorderkredit .select2").eq(1).css({
            border: "0px",
            "border-radius": "10px",
        });
    });

    //RESET MODAL LIST ORDER
    $("#btn-close-list").on("click", function () {
        let btn_element =
            '<div> <button class="btn btn-icon btn-icon-end btn-primary" type="button" id="btn-add-list-order"><span>Tambah</span></button></div>';
        $("#btn-action-list-order").html(btn_element);
        $("#current_unique").val("");
        $("#nama_dealer").val("");
        $("#cmo").val("");
        $("#pic").val("");
        $("#jenis_transaksi").val(null).trigger("change");
        $("#kredit_via_leasing").val(null).trigger("change");
        $("#merk").val("");
        $("#type").val("");
        $("#tahun_pembuatan").val("");
        $("#otr").val("");
        $("#dp_po").val("");
        $("#pencairan").val("");
        $("#dp").val("");
        $("#angsuran").val("");
        $("#tenor").val("");

        $("#nama_dealer").removeClass("is-invalid");
        $("#cmo").removeClass("is-invalid");
        $("#pic").removeClass("is-invalid");
        $("#merk").removeClass("is-invalid");
        $("#type").removeClass("is-invalid");
        $("#tahun_pembuatan").removeClass("is-invalid");
        $("#otr").removeClass("is-invalid");
        $("#dp_po").removeClass("is-invalid");
        $("#pencairan").removeClass("is-invalid");
        $("#dp").removeClass("is-invalid");
        $("#angsuran").removeClass("is-invalid");
        $("#tenor").removeClass("is-invalid");

        $("#nama_dealer").removeAttr("disabled");
        $("#cmo").removeAttr("disabled");
        $("#pic").removeAttr("disabled");
        $("#merk").removeAttr("disabled");
        $("#type").removeAttr("disabled");
        $("#tahun_pembuatan").removeAttr("disabled");
        $("#otr").removeAttr("disabled");
        $("#dp_po").removeAttr("disabled");
        $("#pencairan").removeAttr("disabled");
        $("#dp").removeAttr("disabled");
        $("#angsuran").removeAttr("disabled");
        $("#tenor").removeAttr("disabled");

        $("#select-jenis-transaksi").removeClass("d-none");
        $("#select-via").removeClass("d-none");
        $("#view-jenis-transaksi").addClass("d-none");

        $("#modal-regorderkredit .invalid-jt").remove();
        $("#modal-regorderkredit .invalid-via").remove();

        $("#modal-regorderkredit .select2").eq(0).css({
            border: "0px",
            "border-radius": "10px",
        });
        $("#modal-regorderkredit .select2").eq(1).css({
            border: "0px",
            "border-radius": "10px",
        });
    });

    //PERUBAHAN STATUS REGISSTER ORDER
    //bila disetujui
    $("#datatableBoxed_reg_order_list").on(
        "click",
        ".button-disetujui",
        function () {
            let unique = $(this).attr("data-unique");
            Swal.fire({
                title: "Anda yakin ingin mengubah status menajdi disetujui?",
                text: "Anda bisa mengubahnya jika keliru",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Saya yakin!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        data: { unique: unique },
                        url: "/statusDiSetujui",
                        type: "GET",
                        dataType: "json",
                        success: function (response) {
                            Swal.fire("Pesan!", response.success, "success");
                            table2.ajax.reload();
                        },
                    });
                }
            });
        }
    );
    //bila ditolak
    $("#datatableBoxed_reg_order_list").on(
        "click",
        ".button-ditolak",
        function () {
            let unique = $(this).attr("data-unique");
            Swal.fire({
                title: "Anda yakin ingin mengubah status menjadi ditolak?",
                text: "Anda bisa mengubahnya jika keliru",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Saya yakin!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        data: { unique: unique },
                        url: "/statusDiTolak",
                        type: "GET",
                        dataType: "json",
                        success: function (response) {
                            Swal.fire("Pesan!", response.success, "success");
                            table2.ajax.reload();
                        },
                    });
                }
            });
        }
    );
    //bila diajukan
    $("#datatableBoxed_reg_order_list").on(
        "click",
        ".button-proses",
        function () {
            let unique = $(this).attr("data-unique");
            Swal.fire({
                title: "Anda yakin ingin mengubah status menjadi diproses?",
                text: "Anda bisa mengubahnya jika keliru",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Saya yakin!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        data: { unique: unique },
                        url: "/statusDiProses",
                        type: "GET",
                        dataType: "json",
                        success: function (response) {
                            Swal.fire("Pesan!", response.success, "success");
                            table2.ajax.reload();
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
        $("textarea.form-control").removeClass("is-invalid");
        $("div.invalid-feedback").remove();

        // menampilkan pesan error baru
        $.each(errors, function (field, messages) {
            let inputElement = $("input[name=" + field + "]");
            // let selectElement = $("select[name=" + field + "]");
            let textAreaElement = $("textarea[name=" + field + "]");
            let feedbackElement = $(
                '<div class="invalid-feedback ml-2"></div>'
            );

            $.each(messages, function (index, message) {
                feedbackElement.append(
                    $('<p class="p-0 m-0 text-center">' + message + "</p>")
                );
            });

            if (inputElement.length > 0) {
                inputElement.addClass("is-invalid");
                inputElement.after(feedbackElement);
            }

            // if (selectElement.length > 0) {
            //     selectElement.addClass("is-invalid");
            //     selectElement.after(feedbackElement);
            // }

            if (textAreaElement.length > 0) {
                textAreaElement.addClass("is-invalid");
                textAreaElement.after(feedbackElement);
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
            textAreaElement.each(function () {
                textAreaElement.on("click", function () {
                    $(this).removeClass("is-invalid");
                });
            });
            // selectElement.each(function () {
            //     selectElement.on("click", function () {
            //         $(this).removeClass("is-invalid");
            //     });
            // });
        });
    }

    //HAPUS DATA REGORDER
    $("#datatableBoxed_reg_order_kredit").on(
        "click",
        ".delete-button-regorder",
        function () {
            let unique = $(this).attr("data-unique");
            let token = $(this).attr("data-token");
            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Data akan dihapus permanen dan tidak bisa dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        data: {
                            _token: token,
                            _method: "DELETE",
                        },
                        url: "/regorderkredit/" + unique,
                        type: "POST",
                        dataType: "json",
                        success: function (response) {
                            // console.log(response);
                            Swal.fire("Pesan!", response.success, "success");
                            table.ajax.reload();
                        },
                    });
                }
            });
        }
    );
    //Hapus data list order
    $("#datatableBoxed_reg_order_list").on(
        "click",
        ".delete-button-list-order",
        function () {
            let unique = $(this).attr("data-unique");
            let token = $(this).attr("data-token");
            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Data akan dihapus permanen dan tidak bisa dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        data: {
                            _token: token,
                            _method: "DELETE",
                        },
                        url: "/listorder/" + unique,
                        type: "POST",
                        dataType: "json",
                        success: function (response) {
                            // console.log(response);
                            Swal.fire("Pesan!", response.success, "success");
                            table2.ajax.reload();
                        },
                    });
                }
            });
        }
    );
});

//Show Gambar KTP
function previewImageKTP() {
    document.getElementById("photo_ktp").classList.remove("is-invalid");
    document.getElementById("photo-ktp").classList.remove("is-invalid");
    const image = document.querySelector("#photo-ktp");
    const imgPre = document.querySelector("#img-ktp img");
    const photo_ktp = document.querySelector("#photo_ktp");

    const oFReader = new FileReader();
    oFReader.readAsDataURL(image.files[0]);
    oFReader.onload = function (oFREvent) {
        imgPre.src = oFREvent.target.result;
        photo_ktp.value = oFREvent.target.result;
    };
}
