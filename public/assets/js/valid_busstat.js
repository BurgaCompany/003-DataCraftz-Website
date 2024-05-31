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
