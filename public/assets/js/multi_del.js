$(function () {
    $("#select_all_ids").click(function () {
        $(".checkbox_ids").prop("checked", $(this).prop("checked"));
    });

    $("#confirmDelete").click(function () {
        // Mendapatkan URL dari atribut data-url
        var url = $("#deleteAllSelectedRecord").data("url");

        // Mendapatkan token CSRF dari meta tag
        var csrfToken = $('meta[name="csrf-token"]').attr("content");

        // Deklarasikan variabel all_ids dan berikan nilai yang sesuai
        var all_ids = []; // Misalnya, Anda dapat menginisialisasi dengan array kosong

        // Mengumpulkan ID yang ingin dihapus, misalnya dari input checkbox
        $("input.checkbox_ids:checked").each(function () {
            all_ids.push($(this).val());
        });

        // Lakukan permintaan Ajax dengan metode POST
        $.ajax({
            url: url,
            type: "POST",
            data: {
                ids: all_ids,
                _token: csrfToken,
            },
            success: function (response) {
                // Hapus elemen yang dihapus dari halaman
                $.each(all_ids, function (key, val) {
                    $("#upt_ids_" + val).remove();
                });

                // Tampilkan pesan flash jika ada dalam respons JSON
                if (response.success) {
                    // Menggunakan Toastr untuk menampilkan pesan flash
                    toastr.success("Berhasil menghapus data");
                }

                // Lakukan refresh halaman untuk menampilkan pesan flash
                location.reload();
            },

            error: function (xhr, textStatus, errorThrown) {
                console.error(xhr.responseText); // Log pesan kesalahan ke konsol
                // Handle error
                alert("Mohon pilih satu data terlebih dahulu");
            },
        });
        // Cetak nilai all_ids ke konsol untuk memeriksa nilai yang dikirimkan
        console.log(all_ids);
        // Tutup modal peringatan setelah melakukan request AJAX
        $("#confirmationModal").modal("hide");
    });
});
