$(document).ready(function () {
    $("#modal").on("keyup", function () {
        $("input.money").simpleMoneyFormat({
            currencySymbol: "Rp",
            decimalPlaces: 0,
            thousandsSeparator: ".",
        });
    });

    //Action Simpan
    $("#save-data-modal").on("click", function () {
        let formdata = $("#modal-halaman-modal form").serializeArray();
        let data = {};
        $(formdata).each(function (index, obj) {
            data[obj.name] = obj.value;
        });
        $.ajax({
            data: $("#modal-halaman-modal form").serialize(),
            url: "/modal/" + $("#unique").val(),
            type: "POST",
            dataType: "json",
            success: function (response) {
                if (response.errors) {
                    displayErrors(response.errors);
                } else {
                    $("#modal-halaman-modal").modal("hide");
                    Swal.fire("Good job!", response.success, "success");
                    $.ajax({
                        url: "/refreshPage",
                        success: function (response) {
                            $("#refresh-modal").html(
                                new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR",
                                    minimumFractionDigits: 0,
                                })
                                    .format(response.success.data)
                                    .replace("Rp", "Rp.")
                            );
                            $("#refresh-asset").html(
                                new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR",
                                    minimumFractionDigits: 0,
                                })
                                    .format(response.success.bike_sele)
                                    .replace("Rp", "Rp.")
                            );
                            $("#refresh-sisa").html(
                                new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR",
                                    minimumFractionDigits: 0,
                                })
                                    .format(response.success.sisa_modal)
                                    .replace("Rp", "Rp.")
                            );
                            $("#refresh-laba").html(
                                new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR",
                                    minimumFractionDigits: 0,
                                })
                                    .format(response.success.laba)
                                    .replace("Rp", "Rp.")
                            );
                        },
                    });
                }
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
        });
    }
});
