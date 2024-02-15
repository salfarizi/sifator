$(document).ready(function () {
    let table = $("#dataTablesUser").DataTable({
        processing: true,
        responsive: true,
        searching: true,
        bLengthChange: true,
        info: false,
        ordering: true,
        serverSide: true,
        ajax: "/datatablesUser",
        dom: '<"row"<"col-sm-12"<"table-container"<"card-body half-padding"f><"card"<"card-body half-padding"t>>>>><"row"<"col-12 mt-3"p>>', // Hiding all other dom elements except table and pagination
        pageLength: 15,
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#dataTablesUser").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "name",
            },
            {
                data: "roles_edit",
                render: function (data, type, row, meta) {
                    let split = data.split("-");
                    return type === "display"
                        ? '<p class="view-roles view-roles-' +
                              split[0] +
                              '" data-id="' +
                              split[0] +
                              '" style="cursor: pointer;">' +
                              split[1] +
                              "</p>" +
                              '<div class="d-none edit-roles edit-roles-' +
                              split[0] +
                              '"></div>'
                        : data;
                },
            },
            {
                data: "email",
            },
            {
                data: "action",
                orderable: false,
                searchable: true,
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
        order: [[1, "asc"]],
        language: {
            paginate: {
                previous: '<i class="cs-chevron-left"></i>',
                next: '<i class="cs-chevron-right"></i>',
            },
        },
    });
    //UBAH ROLES
    $("#dataTablesUser").on("click", ".view-roles", function () {
        let id = $(this).attr("data-id");
        $("#dataTablesUser .edit-roles").addClass("d-none");
        table.ajax.reload();
        $.ajax({
            data: { id: id },
            url: "/changeRoles",
            type: "GET",
            success: function (response) {
                $("#dataTablesUser .view-roles-" + id).addClass("d-none");
                $("#dataTablesUser .edit-roles-" + id).removeClass("d-none");
                $("#dataTablesUser .edit-roles-" + id).html(response);
                //UPDATE ROLES
                $("#dataTablesUser #role_user").on("change", function () {
                    let formdata = $(
                        "#dataTablesUser #form_role"
                    ).serializeArray();
                    let data = {};
                    $(formdata).each(function (index, obj) {
                        data[obj.name] = obj.value;
                    });
                    $.ajax({
                        data: $("#dataTablesUser #form_role").serialize(),
                        url: "/updateRoles",
                        type: "POST",
                        dataType: "json",
                        success: function (response) {
                            if (response.error) {
                                table.ajax.reload();
                                Swal.fire("Pesan!", response.error, "warning");
                            } else {
                                $("#dataTablesUser .edit-roles-" + id).addClass(
                                    "d-none"
                                );
                                $("#dataTablesUser .edit-roles-" + id).html("");
                                Swal.fire(
                                    "Pesan!",
                                    response.success,
                                    "success"
                                );
                                table.ajax.reload();
                            }
                        },
                    });
                });
            },
        });
    });

    //UBAH PASSWORD
    $("#dataTablesUser").on("click", ".ubah-password-button", function () {
        let unique = $(this).attr("data-unique");
        $("#modal-password").modal("show");
        $("#unique").val(unique);
    });
    //ACTION UBAH PASSWORD
    $("#modal-password").on("click", "#save-data", function () {
        let formdata = $("#modal-password form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        console.log(formdata);
        $.ajax({
            data: $("#modal-password form").serialize(),
            url: "/changePassword",
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    displayErrors(response.errors);
                } else {
                    $("#unique").val("");
                    $("#password").val("");
                    $("#password_confirmation").val("");
                    $("#modal-password").modal("hide");
                    Swal.fire("Pesan!", response.success, "success");
                    table.ajax.reload();
                }
            },
        });
    });
    // HAPUS USER
    $("#dataTablesUser").on("click", ".hapus-button", function () {
        let unique = $(this).attr("data-unique");
        let token = $(this).attr("data-token");
        Swal.fire({
            title: "Yakin ingin menghapus user?",
            text: "Anda akan menghapus user",
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
                    url: "/user/" + unique,
                    type: "POST",
                    dataType: "json",
                    success: function (response) {
                        if (response.error) {
                            table.ajax.reload();
                            Swal.fire("Pesan!", response.error, "warning");
                        } else {
                            Swal.fire("Pesan!", response.success, "success");
                            table.ajax.reload();
                        }
                    },
                });
            }
        });
    });
    //RESET FORM
    $("#modal-password").on("click", ".btn-close-modal", function () {
        $("#modal-password").modal("hide");
        $("#unique").val("");
        $("#password").val("");
        $("#password_confirmation").val("");
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
