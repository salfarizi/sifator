$(document).ready(function () {
    let table = $("#datatableBoxed_penjualan_roles").DataTable({
        processing: true,
        responsive: true,
        searching: true,
        bLengthChange: true,
        info: false,
        ordering: true,
        serverSide: true,
        ajax: "/datatablesRoles",
        dom: '<"row"<"col-sm-12"<"table-container"<"card-body half-padding"f><"card"<"card-body half-padding"t>>>>><"row"<"col-12 mt-3"p>>', // Hiding all other dom elements except table and pagination
        // Hiding all other dom elements except table and pagination
        pageLength: 15,
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#datatableBoxed_penjualan_roles")
                        .DataTable()
                        .page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "name",
            },
            {
                data: "action",
            },
        ],
        columnDefs: [
            {
                targets: [2], // index kolom atau sel yang ingin diatur
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
    let table2 = $("#table-access").DataTable({
        processing: true,
        responsive: true,
        searching: true,
        bLengthChange: true,
        info: false,
        ordering: true,
        dom: '<"row"<"col-sm-12"<"table-container"<"card-body half-padding"f><"card"<"card-body half-padding"t>>>>><"row"<"col-12 mt-3"p>>', // Hiding all other dom elements except table and pagination
        // Hiding all other dom elements except table and pagination
        pageLength: 15,
        language: {
            paginate: {
                previous: '<i class="cs-chevron-left"></i>',
                next: '<i class="cs-chevron-right"></i>',
            },
        },
    });
    $(".add-data").on("click", function () {
        let btn_elemet =
            '<button type="button" class="btn btn-secondary" id="save-data">Tambah</button>';
        $("#btn-action").html(btn_elemet);
    });
    $("#modal-roles").on("click", "#save-data", function () {
        let formdata = $("#modal-roles form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-roles form").serialize(),
            url: "/roles",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    displayErrors(response.errors);
                } else {
                    $("#name").val("");
                    $("#modal-roles").modal("hide");
                    Swal.fire("Pesan!", response.success, "success");
                    table.ajax.reload();
                }
            },
        });
    });

    // HAPUS DATA ROLES
    $("#datatableBoxed_penjualan_roles").on(
        "click",
        ".delete-button-roles",
        function () {
            let unique = $(this).attr("data-unique");
            let token = $(this).attr("data-token");
            Swal.fire({
                title: "Yakin ingin menghapus roles?",
                text: "Anda akan menghapus roles",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        data: {
                            unique: unique,
                            _method: "DELETE",
                            _token: token,
                        },
                        url: "/roles/" + unique,
                        type: "POST",
                        dataType: "json",
                        success: function (response) {
                            Swal.fire("Pesan!", response.success, "success");
                            table.ajax.reload();
                        },
                    });
                }
            });
        }
    );
    // ACCESS BUTTON
    $("#datatableBoxed_penjualan_roles").on(
        "click",
        ".access-button",
        function () {
            let unique = $(this).attr("data-unique");
            $("#unique_access").val(unique);
            $("#modal-access").modal("show");
            $.ajax({
                data: { unique: unique },
                url: "/refresh_access",
                type: "GET",
                success: function (response) {
                    $("#list-access").html(response);
                },
            });
        }
    );

    //RESET MODAL ACCESS
    $(".btn-close-access").on("click", function () {
        $("#unique_access").val("");
    });

    //TAMBAH ACCESS
    $("#modal-access").on("click", "#tambah-access", function () {
        let menu = $(this).attr("data-menu");
        let unique = $("#unique_access").val();
        $.ajax({
            data: { unique: unique, menu: menu },
            url: "/tambah_access",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $.ajax({
                    data: { unique: unique },
                    url: "/refresh_access",
                    type: "GET",
                    success: function (response) {
                        $("#list-access").html(response);
                    },
                });
            },
        });
    });
    //KURANG ACCESS
    $("#modal-access").on("click", "#hapus-access", function () {
        let menu = $(this).attr("data-menu");
        let unique = $("#unique_access").val();
        $.ajax({
            data: { unique: unique, menu: menu },
            url: "/hapus_access",
            type: "GET",
            dataType: "json",
            success: function (response) {
                $.ajax({
                    data: { unique: unique },
                    url: "/refresh_access",
                    type: "GET",
                    success: function (response) {
                        $("#list-access").html(response);
                    },
                });
            },
        });
    });

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
            textAreaElement.each(function () {
                textAreaElement.on("click", function () {
                    $(this).removeClass("is-invalid");
                });
            });
            selectElement.each(function () {
                selectElement.on("click", function () {
                    $(this).removeClass("is-invalid");
                });
            });
        });
    }
});
