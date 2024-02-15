$(document).ready(function () {
    let table = $("#datatableBoxed_pembelian").DataTable({
        processing: true,
        responsive: true,
        searching: true,
        bLengthChange: true,
        info: false,
        ordering: true,
        serverSide: true,
        ajax: "/datatablesPembelian",
        dom: '<"row"<"col-sm-12"<"table-container"<"card-body half-padding"f><"card"<"card-body half-padding"t>>>>><"row"<"col-12 mt-3"p>>', // Hiding all other dom elements except table and pagination
        pageLength: 15,
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#datatableBoxed_pembelian")
                        .DataTable()
                        .page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "nota",
            },
            {
                data: "merek",
                orderable: false,
                searchable: true,
            },
            {
                data: "type",
                orderable: false,
                searchable: true,
            },
            {
                data: "no_polisi",
                orderable: false,
            },
            {
                data: "warna",
                orderable: false,
            },
            {
                data: "tgl_beli",
            },
            {
                data: "harga",
            },
            {
                data: "action",
                orderable: false,
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
        order: [[1, "desc"]],
        language: {
            paginate: {
                previous: '<i class="cs-chevron-left"></i>',
                next: '<i class="cs-chevron-right"></i>',
            },
        },
    });

    // RESET
    // $("#nik").on('click', function () {
    //     $("#nik").removeClass("is-invalid")
    // })
    // $("#nama").on('click', function () {
    //     $("#nama").removeClass("is-invalid")
    // })
    // $("#tempat_lahir").on('click', function () {
    //     $("#tempat_lahir").removeClass("is-invalid")
    //     $("#tanggal_lahir").removeClass("is-invalid")
    // })
    // $("#tanggal_lahir").on('click', function () {
    //     $("#tempat_lahir").removeClass("is-invalid")
    //     $("#tanggal_lahir").removeClass("is-invalid")
    // })
    // $("#alamat").on('click', function () {
    //     $("#alamat").removeClass("is-invalid")
    // })
    // $("#merek").on('click', function () {
    //     $("#merek").removeClass("is-invalid")
    // })
    // $("#daya").on('click', function () {
    //     $("#daya").removeClass("is-invalid")
    // })
    // $("#no_rangka").on('click', function () {
    //     $("#no_rangka").removeClass("is-invalid")
    // })
    // $("#bpkb").on('click', function () {
    //     $("#bpkb").removeClass("is-invalid")
    // })
    // $("#type").on('click', function () {
    //     $("#type").removeClass("is-invalid")
    // })
    // $("#bahan_bakar").on('click', function () {
    //     $("#bahan_bakar").removeClass("is-invalid")
    // })
    // $("#no_polisi").on('click', function () {
    //     $("#no_polisi").removeClass("is-invalid")
    // })
    // $("#berlaku_sampai").on('click', function () {
    //     $("#berlaku_sampai").removeClass("is-invalid")
    // })
    // $("#harga_beli").on('click', function () {
    //     $("#harga_beli").removeClass("is-invalid")
    // })
    // $("#tanggal_beli").on('click', function () {
    //     $("#tanggal_beli").removeClass("is-invalid")
    // })

    $("#nik").on("keyup", function () {
        NProgress.start();
        let nik = $("#nik").val();
        $.ajax({
            data: {
                nik: nik,
            },
            url: "/cekNIK",
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#nama").val(response.success.nama);
                    $("#tempat_lahir").val(response.success.tempat_lahir);
                    $("#tanggal_lahir").val(response.success.tanggal_lahir);
                    $("#jenis_kelamin").val(response.success.jenis_kelamin);
                    $("#no_telepon").val(response.success.no_telepon);
                    $("#alamat").html(response.success.alamat);
                    $(".image-ktp").attr(
                        "src",
                        "/storage/" + response.success.photo_ktp
                    );
                    $("#oldKTP").val(response.success.photo_ktp);
                    NProgress.done();
                } else {
                    $("#nama").val("");
                    $("#tempat_lahir").val("");
                    $("#tanggal_lahir").val("");
                    $("#jenis_kelamin").val("Laki-Laki");
                    $("#no_telepon").val("");
                    $("#alamat").html("");
                    $(".image-ktp").attr("src", "/storage/ktp/default.png");
                    $("#oldKTP").val("");
                    NProgress.done();
                }
            },
        });
    });

    // Popup Modal Detail
    $("#datatableBoxed_pembelian").on("click", ".info-button", function () {
        $("#table_konsumen tbody .info-ktp").remove();
        let id = $(this).attr("data-id");
        $("#modal-detail").modal("show");

        $.ajax({
            data: {
                id: id,
            },
            url: "/getDataTransaksi",
            type: "GET",
            dataType: "json",
            success: function (response) {
                // console.log(response);
                // console.log(response.success.consumer.nik)
                if (response.success.consumer.penjual == "INDIVIDU") {
                    $("#data-individu").removeClass("d-none");
                    $("#data-dealer").addClass("d-none");
                    $("#nik").html(response.success.consumer.nik);
                    $("#nama").html(response.success.consumer.nama);
                    $("#no-telepon").html(response.success.consumer.no_telepon);
                    $("#alamat").html(response.success.consumer.alamat);
                    $("#table_konsumen tbody").append(
                        '<tr class="info-ktp"><td>Photo KTP</td><td>:</td><td><button data-img="/storage/' +
                            response.success.consumer.photo_ktp +
                            '" class="btn btn-sm btn-primary rounded text-white look-img-ktp">Lihat Gambar</button></td></tr>'
                    );
                } else if (response.success.consumer.penjual == "DEALER") {
                    $("#data-dealer").removeClass("d-none");
                    $("#data-individu").addClass("d-none");
                    $("#nama-petugas").html(response.success.consumer.nama);
                    $("#dealer").html(response.success.consumer.dealer);
                }

                $("#no-polisi").html(response.success.motor.no_polisi);
                $("#merk").html(response.success.motor.merek);
                $("#tipe").html(response.success.motor.type);
                $("#warna").html(response.success.motor.warna);
                $("#tahun-pembuatan").html(
                    response.success.motor.tahun_pembuatan
                );
                $("#no-rangka").html(response.success.motor.no_rangka);
                $("#no-mesin").html(response.success.motor.no_mesin);
                $("#nama-bpkb").html(response.success.motor.nama_bpkb);
                $("#alamat-bpkb").html(response.success.motor.alamat_bpkb);
                $("#bpkb").html(response.success.motor.bpkb);
                $("#berlaku-sampai").html(response.success.berlaku_sampai);
                $("#perpanjang-stnk").html(response.success.perpanjang_stnk);
                $("#foto-bpkb").html(
                    '<button data-img="/storage/' +
                        response.success.motor.photo_bpkb +
                        '" class="btn btn-sm btn-primary rounded text-white look-img-bpkb">Lihat Gambar</button>'
                );
                $("#foto-stnk").html(
                    '<button data-img="/storage/' +
                        response.success.motor.photo_stnk +
                        '" class="btn btn-sm btn-primary rounded text-white look-img-stnk">Lihat Gambar</button>'
                );

                $("#tanggal-beli").html(response.success.tanggal_beli);
                $("#harga-beli").html(response.success.harga);
            },
        });
    });

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

    function displayErrors(errors) {
        // menghapus class 'is-invalid' dan pesan error sebelumnya
        $("input.form-control").removeClass("is-invalid");
        $("div.invalid-feedback").remove();

        // menampilkan pesan error baru
        $.each(errors, function (field, messages) {
            var inputElement = $("input[name=" + field + "]");
            var selectElement = $("select[name=" + field + "]");
            var textAreaElement = $("textarea[name=" + field + "]");
            var feedbackElement = $('<div class="invalid-feedback"></div>');

            $.each(messages, function (index, message) {
                feedbackElement.append($("<p>" + message + "</p>"));
            });

            if (inputElement.length > 0) {
                inputElement.addClass("is-invalid");
                inputElement.after(feedbackElement);
            }

            if (selectElement.length > 0) {
                selectElement.addClass("is-invalid");
                selectElement.after(feedbackElement);
            }

            if (textAreaElement.length > 0) {
                textAreaElement.addClass("is-invalid");
                textAreaElement.after(feedbackElement);
            }
        });
    }
    //Lihat Photo STNK
    $("#modal-detail").on("click", ".look-img-stnk", function () {
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
    //Lihat Foto BPKB
    $("#modal-detail").on("click", ".look-img-bpkb", function () {
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
    //Lihat Foto KTP
    $("#modal-detail").on("click", ".look-img-ktp", function () {
        let image = $(this).attr("data-img");
        // alert(image);
        $("#img-photo").html(
            '<img src="' +
                image +
                '" alt="" class="img-fluid" style="width: 800px">'
        );
        $("#judul-modal-photo").html("Photo KTP");
        $("#modal-image").modal("show");
    });

    //LOAD CONTENT CONSUMER
    $(".penjual").on("change", function () {
        $(".penjual").removeClass("is-invalid");
        if ($(".penjual").val() == "INDIVIDU") {
            $(".tab-content").css({
                height: "auto",
            });
            $("#consumer-content-dealer").addClass("d-none");
            $("#consumer-content-individu").removeClass("d-none");
            $("#nama_kang").val("");
            $("#dealer").val("");
        } else if ($(".penjual").val() == "DEALER") {
            $(".tab-content").css({
                height: "auto",
            });
            $("#consumer-content-individu").addClass("d-none");
            $("#consumer-content-dealer").removeClass("d-none");
            $("#nama").val("");
            $("#nik").val("");
            $("#alamat").val("");
            $("#no_telepon").val("");
        } else if ($(".penjual").val() == "") {
            $(".tab-content").css({
                height: "auto",
            });
            $("#consumer-content-individu").addClass("d-none");
            $("#consumer-content-dealer").addClass("d-none");
            $("#nama_kang").val("");
            $("#dealer").val("");
            $("#nama").val("");
            $("#nik").val("");
            $("#alamat").val("");
            $("#no_telepon").val("");
        }
    });
    $("#button-no-modal").on("click", function () {
        //Alert jika modal 0
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Anda Belum Memasukan Modal, Silahkan Input Modal",
            footer: '<a href="/modal">Input Modal Disini</a>',
        });
    });

    //HAPUS DATA PEMBELIAN
    $("#datatableBoxed_pembelian").on(
        "click",
        ".delete-button-pembelian",
        function () {
            let token = $(this).attr("data-token");
            let unique = $(this).attr("data-unique");
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
                        url: "/pembelian/" + unique,
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
});

function previewImageBPKB() {
    const image = document.querySelector("#photo_bpkb");
    const imgPre = document.querySelector(".image-bpkb");

    const oFReader = new FileReader();
    oFReader.readAsDataURL(image.files[0]);
    oFReader.onload = function (oFREvent) {
        imgPre.src = oFREvent.target.result;
    };
}

function previewImageKTP() {
    const image = document.querySelector("#photo_ktp");
    const imgPre = document.querySelector(".image-ktp");

    const oFReader = new FileReader();
    oFReader.readAsDataURL(image.files[0]);
    oFReader.onload = function (oFREvent) {
        imgPre.src = oFREvent.target.result;
    };
}

function previewImageSTNK() {
    const image = document.querySelector("#photo_stnk");
    const imgPre = document.querySelector(".image-stnk");

    const oFReader = new FileReader();
    oFReader.readAsDataURL(image.files[0]);
    oFReader.onload = function (oFREvent) {
        imgPre.src = oFREvent.target.result;
    };
}
