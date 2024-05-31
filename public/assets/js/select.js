document.addEventListener("DOMContentLoaded", function () {
    // Fungsi untuk mengecek apakah minimal satu sopir dan satu konduktor telah dipilih
    function validateSelection() {
        var driversSelect = document.getElementById("drivers");
        var busConductorsSelect = document.getElementById("bus_conductors");
        var selectedDriversCount = driversSelect.selectedOptions.length;
        var selectedBusConductorsCount =
            busConductorsSelect.selectedOptions.length;

        // Cetak nilai selectedDriversCount dan selectedBusConductorsCount ke konsol
        console.log("Jumlah sopir yang dipilih:", selectedDriversCount);
        console.log(
            "Jumlah konduktor yang dipilih:",
            selectedBusConductorsCount
        );

        // Cek apakah minimal satu sopir **atau** minimal satu konduktor telah dipilih
        if (
            (selectedDriversCount === 0 && selectedBusConductorsCount !== 0) ||
            (selectedDriversCount !== 0 && selectedBusConductorsCount === 0)
        ) {
            // Tampilkan modal peringatan jika salah satu field diisi dan yang lainnya kosong
            $("#exampleModalInvalid").modal("show");
            return false; // Mencegah modal "Ubah" muncul
        }
        return true; // Izinkan modal "Ubah" muncul karena validasi berhasil
    }

    // Dapatkan referensi ke tombol "Ubah"
    var ubahButton = document.querySelector('[data-target="#exampleModal"]');

    // Tambahkan event listener ke tombol "Ubah" untuk melakukan validasi sebelum menampilkan modal
    ubahButton.addEventListener("click", function (event) {
        // Lakukan validasi saat tombol "Ubah" diklik
        if (!validateSelection()) {
            // Jika validasi gagal, hentikan event (jangan tampilkan modal "Ubah")
            event.stopPropagation();
        }
    });
});
