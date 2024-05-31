document.addEventListener("DOMContentLoaded", function () {
    var ctx = document.getElementById("userReservationsChart").getContext("2d");
    var userReservationsChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: JSON.parse(ctx.canvas.dataset.dates), // gunakan data dari dataset
            datasets: [
                {
                    label: "Jumlah Pemesanan",
                    data: JSON.parse(ctx.canvas.dataset.reservationsCount), // gunakan data dari dataset
                    backgroundColor: "rgba(54, 162, 235, 0.2)", // warna background
                    borderColor: "rgba(54, 162, 235, 1)", // warna garis
                    borderWidth: 1, // ketebalan garis
                },
            ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });
});
