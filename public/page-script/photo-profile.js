$(document).ready(function () {
    let modal = $("#modal-cropper");
    let image = document.getElementById("image");
    let cropper, reader, file;
    $("body").on("change", "#photo_pria", function (e) {
        $(".crop_gallery").addClass("d-none");
        $(".crop_wanita").addClass("d-none");
        $(".crop_pria").removeClass("d-none");
        let files = e.target.files;
        let done = function (url) {
            image.src = url;
            modal.modal("show");
        };

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    $(".crop_pria").on("click", function () {
        canvas = cropper.getCroppedCanvas({
            width: 900,
            height: 900,
        });

        canvas.toBlob(function (blob) {
            const imgPre = document.querySelector(".show-foto-pria");
            const foto = document.querySelector("#fotoPria");
            const oFReader = new FileReader();
            oFReader.readAsDataURL(blob);
            oFReader.onload = function (oFREvent) {
                imgPre.src = oFREvent.target.result;
                foto.value = oFREvent.target.result;
            };
        });

        modal.modal("hide");
    });

    $("body").on("change", "#photo_wanita", function (e) {
        $(".crop_gallery").addClass("d-none");
        $(".crop_pria").addClass("d-none");
        $(".crop_wanita").removeClass("d-none");
        let files = e.target.files;
        let done = function (url) {
            image.src = url;
            modal.modal("show");
        };

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    $(".crop_wanita").on("click", function () {
        canvas = cropper.getCroppedCanvas({
            width: 900,
            height: 900,
        });

        canvas.toBlob(function (blob) {
            const imgPre = document.querySelector(".show-foto-wanita");
            const foto = document.querySelector("#fotoWanita");
            const oFReader = new FileReader();
            oFReader.readAsDataURL(blob);
            oFReader.onload = function (oFREvent) {
                imgPre.src = oFREvent.target.result;
                foto.value = oFREvent.target.result;
            };
        });

        modal.modal("hide");
    });

    modal
        .on("shown.bs.modal", function () {
            cropper = new Cropper(image, {
                aspectRatio: 1 / 1,
                preview: ".preview",
            });
        })
        .on("hidden.bs.modal", function () {
            cropper.destroy();
            cropper = null;
        });

    $("body").on("change", "#photo", function (e) {
        let files = e.target.files;
        let done = function (url) {
            image.src = url;
            modal.modal("show");
        };

        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    $(".crop_gallery").on("click", function () {
        canvas = cropper.getCroppedCanvas({
            width: 900,
            height: 900,
        });

        canvas.toBlob(function (blob) {
            const foto = document.querySelector("#base64img");
            const oFReader = new FileReader();
            oFReader.readAsDataURL(blob);
            oFReader.onload = function (oFREvent) {
                foto.value = oFREvent.target.result;
            };
        });

        modal.modal("hide");
    });

    // Sweet-Alert
    const flashData = $(".flash-data").data("flashdata");
    if (flashData) {
        Swal.fire("Good job!", flashData, "success");
    }

    //Konfirmasi Password
    $(".button-conf").on("click", function () {
        $("#conf_password").modal("show");
    });
});
