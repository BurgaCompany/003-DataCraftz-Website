$(document).ready(function () {
    var $modal = $("#modal");
    var image = document.getElementById("sample_image");
    var cropper;

    $("#upload_image").change(function (event) {
        var files = event.target.files;
        var done = function (url) {
            image.src = url;
            $modal.modal("show");
        };

        if (files && files.length > 0) {
            var reader = new FileReader();
            reader.onload = function (event) {
                done(event.target.result);
            };
            reader.readAsDataURL(files[0]);
        }
    });

    $modal
        .on("shown.bs.modal", function () {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 3,
                preview: ".preview",
            });
        })
        .on("hidden.bs.modal", function () {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

    $("#crop").click(function () {
        if (cropper) {
            canvas = cropper.getCroppedCanvas({
                width: 400,
                height: 400,
            });

            canvas.toBlob(function (blob) {
                var formData = new FormData();
                formData.append("image", blob);

                var apiUrl = $("#modal").data("url");

                // Kirim gambar yang dipotong ke server
                $.ajax({
                    url: apiUrl,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    success: function (data) {
                        $modal.modal("hide");
                        $("#uploaded_image").attr("src", data);
                        // Me-refresh halaman setelah 1 detik
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        // Handle error if needed
                    },
                });
            });
        }
    });
});
