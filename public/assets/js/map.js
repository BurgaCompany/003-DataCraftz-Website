document.addEventListener("DOMContentLoaded", function () {
    // Initialize the map with a higher zoom level
    var map = L.map("map").setView([-6.2, 106.816666], 17);

    // Add OpenStreetMap tile layer
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    var marker;

    // Check if latitude and longitude inputs have values
    var latInput = document.getElementById("latitude").value;
    var lngInput = document.getElementById("longitude").value;

    if (latInput && lngInput) {
        var initialLat = parseFloat(latInput);
        var initialLng = parseFloat(lngInput);

        // Set initial view and marker with a higher zoom level
        map.setView([initialLat, initialLng], 17);
        marker = L.marker([initialLat, initialLng]).addTo(map);
    }

    var geocoder = L.Control.geocoder({
        defaultMarkGeocode: false,
        placeholder: "Cari lokasi...",
    })
        .on("markgeocode", function (e) {
            var latlng = e.geocode.center;
            map.setView(latlng, 17);

            if (marker) {
                marker.setLatLng(latlng);
            } else {
                marker = L.marker(latlng).addTo(map);
            }

            // Update form input values
            document.getElementById("latitude").value = latlng.lat;
            document.getElementById("longitude").value = latlng.lng;

            // Reverse geocode to get the address
            var geocodeService = L.Control.Geocoder.nominatim();
            geocodeService.reverse(
                latlng,
                map.options.crs.scale(map.getZoom()),
                function (results) {
                    var result = results[0];
                    if (result) {
                        document.getElementById("address").value = result.name;
                    }
                }
            );
        })
        .addTo(map);

    // Event listener for map clicks
    map.on("click", function (e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }

        // Update form input values
        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lng;
    });
});
