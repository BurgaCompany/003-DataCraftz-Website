document.addEventListener("DOMContentLoaded", function () {
    document
        .getElementById("departureTerminal")
        .addEventListener("change", function () {
            checkDepartureTerminal();
        });

    document
        .getElementById("arrivalTerminal")
        .addEventListener("change", function () {
            checkArrivalTerminal();
        });

    function checkDepartureTerminal() {
        var departureTerminal =
            document.getElementById("departureTerminal").value;
        var errorMessage = document.getElementById(
            "departure-terminal-error-message"
        );

        if (departureTerminal.trim() === "") {
            errorMessage.innerText = "Terminal berangkat harus dipilih";
            errorMessage.style.display = "block";
        } else {
            errorMessage.style.display = "none";
        }
    }

    function checkArrivalTerminal() {
        var arrivalTerminal = document.getElementById("arrivalTerminal").value;
        var errorMessage = document.getElementById(
            "arrival-terminal-error-message"
        );

        if (arrivalTerminal.trim() === "") {
            errorMessage.innerText = "Terminal tujuan harus dipilih";
            errorMessage.style.display = "block";
        } else {
            errorMessage.style.display = "none";
        }
    }
});

function validatePrices() {
    var minPriceInput = document.getElementById("min_price");
    var maxPriceInput = document.getElementById("max_price");
    var minPrice = parseFloat(minPriceInput.value);
    var maxPrice = parseFloat(maxPriceInput.value);

    if (minPrice > maxPrice) {
        minPriceInput.setCustomValidity(
            "Harga minimum tidak boleh melebihi harga maksimum."
        );
        maxPriceInput.setCustomValidity(
            "Harga maksimum harus lebih besar dari harga minimum."
        );
    } else if (minPrice === maxPrice) {
        minPriceInput.setCustomValidity(
            "Harga minimum tidak boleh sama dengan harga maksimum."
        );
        maxPriceInput.setCustomValidity(
            "Harga maksimum tidak boleh sama dengan harga minimum."
        );
    } else {
        minPriceInput.setCustomValidity("");
        maxPriceInput.setCustomValidity("");
    }
}

function validatePrice() {
    var minPrice = parseFloat(document.getElementById("min_price").value) || 0;
    var maxPrice =
        parseFloat(document.getElementById("max_price").value) || Infinity;
    var priceInput = document.getElementById("price");
    var price = parseFloat(priceInput.value);

    if (price < minPrice) {
        priceInput.setCustomValidity(
            "Harga tidak boleh kurang dari harga minimum."
        );
    } else if (price > maxPrice) {
        priceInput.setCustomValidity(
            "Harga tidak boleh lebih dari harga maksimum."
        );
    } else {
        priceInput.setCustomValidity("");
    }
}
