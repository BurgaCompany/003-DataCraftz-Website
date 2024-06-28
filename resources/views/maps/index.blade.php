<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> --}}
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Track</title>
    <style>
        /* Mengatur body dan html untuk mengambil seluruh layar */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        /* Mengatur map untuk mengambil seluruh layar */
        #map {
            height: 100%;
            width: 100%;
        }
    </style>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
</head>

<body>
    <div id="map" style="height: 100vh;"></div>


    {{-- <script src="../../assets/js/map.js"></script> --}}

    <script src="../../assets/js/track.js"></script>

    <!-- socket io -->
    <script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <!-- Leaflet-Geosearch JS -->
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src="https://unpkg.com/leaflet-polylinedecorator/leaflet.polylineDecorator.js"></script>
</body>

</html>
