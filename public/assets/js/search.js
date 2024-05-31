// Mengambil elemen input untuk pencarian
const searchInput = document.getElementById("searchInput");

// Menambahkan event listener untuk input
searchInput.addEventListener("input", function () {
    // Mendapatkan nilai pencarian dari input
    const searchValue = this.value.toLowerCase();

    // Mengambil semua baris tabel kecuali baris header
    const rows = document.querySelectorAll("#uptTable tbody tr");

    rows.forEach((row) => {
        // Mengambil data nama Upt dari setiap baris
        const uptName = row
            .querySelector("td:nth-child(3)")
            .textContent.toLowerCase();

        // Mengambil data alamat Upt dari setiap baris
        const uptAddress = row
            .querySelector("td:nth-child(5)")
            .textContent.toLowerCase();

        // Menentukan apakah harus menampilkan atau menyembunyikan baris
        if (
            searchValue === "" ||
            uptName.includes(searchValue) ||
            uptAddress.includes(searchValue)
        ) {
            // Jika cocok atau pencarian kosong, tampilkan baris tabel
            row.style.display = "table-row";
        } else {
            // Jika tidak cocok, sembunyikan baris tabel
            row.style.display = "none";
        }
    });
});

// Tandai atau lepaskan tanda centang pada semua checkbox saat checkbox "select-all" diubah
document.getElementById("select-all").addEventListener("change", function () {
    var checkboxes = document.querySelectorAll(".row-checkbox");
    checkboxes.forEach(function (checkbox) {
        checkbox.checked = this.checked;
    }, this);
});
