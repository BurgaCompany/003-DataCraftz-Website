document.addEventListener("DOMContentLoaded", function () {
    var map = L.map("track_map").setView(
        [-7.915985771560524, 113.8318605422319],
        12
    ); // Set initial map center and zoom level

    // Add OpenStreetMap tile layer to the map
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
    }).addTo(map);

    // Terminal Bondowoso coordinates
    var terminalBondowoso = L.latLng(-7.915985771560524, 113.8318605422319);

    // Terminal Arjasa coordinates
    var terminalArjasa = L.latLng(-8.1180522, 113.7479288);

    // Create a bus icon
    var busIcon = L.icon({
        iconUrl:
            "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png",
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41],
    });

    // Create a marker at Terminal Bondowoso with the bus icon
    var marker = L.marker(terminalBondowoso, { icon: busIcon }).addTo(map);

    // Animate marker movement from Terminal Bondowoso to Terminal Arjasa
    var duration = 10000; // Animation duration in milliseconds (10 seconds)
    var steps = 10000; // Number of steps for animation
    var stepTime = duration / steps; // Time for each step

    var latStep = (terminalArjasa.lat - terminalBondowoso.lat) / steps;
    var lngStep = (terminalArjasa.lng - terminalBondowoso.lng) / steps;

    function moveMarker() {
        var currentLatLng = marker.getLatLng();
        var newLatLng = L.latLng(
            currentLatLng.lat + latStep,
            currentLatLng.lng + lngStep
        );
        marker.setLatLng(newLatLng);

        // Pan the map to follow the marker
        map.panTo(newLatLng);

        if (marker.getLatLng().equals(terminalArjasa)) {
            clearInterval(timer);
        }
    }

    var timer = setInterval(moveMarker, stepTime);
});
