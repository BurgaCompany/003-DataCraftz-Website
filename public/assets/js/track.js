document.addEventListener("DOMContentLoaded", function () {
    // Initialize the map with a higher zoom level
    var map = L.map("map").setView([-6.2, 106.816666], 17);

    // Add OpenStreetMap tile layer
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    var marker;

    // Function to fetch data from the server
    function fetchData() {
        const queryParams = new URLSearchParams(window.location.search);
        const busId = queryParams.get("bu_id");
        fetch(`http://localhost:8000/api/get-coordinate?bus_id=${busId}`) // Update with your API endpoint
            .then((response) => response.json())
            .then((data) => {
                console.log("Data fetched:", data);
                var lat = parseFloat(data.latitude);
                var lng = parseFloat(data.longitude);

                // Update marker position if it exists, otherwise create a new marker
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    var greenIcon = L.icon({
                        iconUrl:
                            "http://localhost:8000/assets/images/Icon-Marker.png",

                        iconSize: [95, 95], // size of the icon
                        shadowSize: [50, 64], // size of the shadow
                        iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
                        shadowAnchor: [4, 62], // the same for the shadow
                        popupAnchor: [-3, -76], // point from which the popup should open relative to the iconAnchor
                    });
                    marker = L.marker([lat, lng], { icon: greenIcon }).addTo(
                        map
                    );
                }

                // Update map view
                map.setView([lat, lng], 17);

                // Update form input values
                document.getElementById("latitude").value = lat;
                document.getElementById("longitude").value = lng;

                // Reverse geocode to get the address
                var geocodeService = L.Control.Geocoder.nominatim();
                geocodeService.reverse(
                    [lat, lng],
                    map.options.crs.scale(map.getZoom()),
                    function (results) {
                        var result = results[0];
                        if (result) {
                            document.getElementById("address").value =
                                result.name;
                        }
                    }
                );
            })
            .catch((error) => console.error("Error fetching data:", error));
    }

    // Initial fetch of data
    fetchData();

    // Polling to fetch data every 30 seconds (30000 milliseconds)
    setInterval(fetchData, 5000);
});
