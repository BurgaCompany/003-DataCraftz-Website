function showKeterangan() {
    var status = document.getElementById("status").value;
    var keteranganField = document.getElementById("keteranganField");

    if (status == "Terkendala") {
        keteranganField.style.display = "block";
    } else {
        keteranganField.style.display = "none";
    }
}
