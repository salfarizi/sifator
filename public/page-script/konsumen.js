$(document).ready(function () {
    let table = $("#dataTables").DataTable({
        processing: true,
        responsive: true,
        searching: true,
        bLengthChange: true,
        info: false,
        ordering: true,
        serverSide: true,
        ajax: "/datatablesIndividu",
        dom: '<"row"<"col-sm-12"<"table-container"<"card-body half-padding"f><"card"<"card-body half-padding"t>>>>><"row"<"col-12 mt-3"p>>', // Hiding all other dom elements except table and pagination
        // Hiding all other dom elements except table and pagination
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
                data: "nama",
            },
            {
                data: "nik",
            },
            {
                data: "no_telepon",
            },
            {
                data: "alamat",
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

    var table2 = $("#dataTables2").DataTable({
        processing: true,
        responsive: true,
        searching: true,
        bLengthChange: true,
        info: false,
        ordering: true,
        serverSide: true,
        sDom: '<"row"<"col-sm-12"<"table-container"<"card-body half-padding"f><"card"<"card-body half-padding"t>>>>><"row"<"col-12 mt-3"p>>', // Hiding all other dom elements except table and pagination
        ajax: "/datatablesDealer",
        // "columnDefs": [{
        //     "targets": [5], // index kolom atau sel yang ingin diatur
        //     "className": 'status-motor' // kelas CSS untuk memposisikan isi ke tengah
        // }],
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
                data: "nama",
            },
            {
                data: "dealer",
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

    let table3 = $("#dataTablesMotor").DataTable({
        processing: true,
        responsive: true,
        searching: true,
        bLengthChange: false,
        info: false,
        ordering: true,
        serverSide: true,
        ajax: {
            url: "/dataTablesMotor",
            type: "GET",
            data: function (d) {
                d.id = $("#data-motor").val();
            },
        },
        columns: [
            {
                data: null,
                orderable: false,
                render: function (data, type, row, meta) {
                    var pageInfo = $("#dataTablesMotor").DataTable().page.info();
                    var index = meta.row + pageInfo.start + 1;
                    return index;
                },
            },
            {
                data: "merek",
            },
            {
                data: "no_polisi",
            },
            {
                data: "warna",
            },
            {
                data: "tanggal_beli",
            },
            {
                data: "harga_beli",
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
    //Memasukan Gambar Ke Modal
    $("#dataTables").on("click", ".info-button-ktp", function () {
        let ktp = $(this).attr("data-ktp");
        $("#modal-ktp .modal-body img").attr("src", "/storage/" + ktp);
        $("#modal-ktp").modal("show");
    });

    //Memasukan data individu ke modal
    $("#dataTables").on("click", ".info-button-individu", function () {
        document.getElementById("dataTablesMotor").style.width = "53vw";
        let id = $(this).attr("data-id");
        $("#data-motor").val(id);
        $("#modal-motor").modal("show");
        table3.ajax.reload();
    });
    $("#dataTables2").on("click", ".info-button-dealer", function () {
        document.getElementById("dataTablesMotor").style.width = "70vw";
        let id = $(this).attr("data-id");
        $("#data-motor").val(id);
        $("#modal-motor").modal("show");
        table3.ajax.reload();
    });

    $(".tab-comsumer").on("click", function () {
        document.getElementById("dataTables2").style.width = "70vw";
    });
});
