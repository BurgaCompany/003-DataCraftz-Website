$(document).ready(function () {
    // Menyembunyikan tombol "Simpan Perubahan" dan "Batal" saat halaman dimuat
    $("#saveButton").hide();
    $("#cancelButton").hide();
    $("#admins").prop("disabled", true); // Menjadikan select admin menjadi disabled saat halaman dimuat

    // Event handler saat tombol "Edit" ditekan
    $("#editButton").click(function () {
        // Menampilkan tombol "Simpan Perubahan" dan "Batal"
        $("#saveButton").show();
        $("#cancelButton").show();
        // Menyembunyikan tombol "Edit"
        $(this).hide();
        // Mengaktifkan input nama dan alamat
        $("#name, #address").prop("disabled", false);
        // Mengaktifkan select admin
        $("#admins").prop("disabled", false);
    });

    // Event handler saat tombol "Batal" ditekan
    $("#cancelButton").click(function () {
        // Menyembunyikan tombol "Simpan Perubahan" dan "Batal"
        $("#saveButton").hide();
        $("#cancelButton").hide();
        // Menampilkan tombol "Edit"
        $("#editButton").show();
        // Menonaktifkan kembali input nama dan alamat
        $("#name, #address").prop("disabled", true);
        // Menonaktifkan select admin
        $("#admins").prop("disabled", true);
    });
});
