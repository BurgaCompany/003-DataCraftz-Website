// Mengambil elemen input untuk pencarian
const searchInputCard = document.getElementById("searchInputCard");

// Menambahkan event listener untuk input
searchInputCard.addEventListener("input", function () {
    // Mendapatkan nilai pencarian dari input dan mengubahnya menjadi lowercase
    const searchValue = this.value.toLowerCase();

    // Mengambil semua kartu custom
    const cards = document.querySelectorAll(".custom-card");

    cards.forEach((card) => {
        // Mengambil teks konten dari masing-masing kartu dan mengubahnya menjadi lowercase
        const cardContent = card
            .querySelector(".card-text")
            .textContent.toLowerCase();

        // Menentukan apakah harus menampilkan atau menyembunyikan kartu
        if (cardContent.includes(searchValue) || searchValue === "") {
            // Jika cocok atau pencarian kosong, tampilkan kartu
            card.style.display = "flex";
        } else {
            // Jika tidak cocok, sembunyikan kartu
            card.style.display = "none";
        }
    });
});
