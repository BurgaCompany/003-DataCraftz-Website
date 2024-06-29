<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Responsive Admin Dashboard Template">
    <meta name="keywords" content="admin,dashboard">
    <meta name="author" content="stacks">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title -->
    <title>TransGo</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/plugins/font-awesome/css/all.min.css" rel="stylesheet">
    <link href="../../assets/plugins/toastr/toastr.min.css" rel="stylesheet">


    <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">

    <link href="../../assets/plugins/select2/css/select2.min.css" rel="stylesheet">
    <link href="../../assets/plugins/cropper-master/cropper.min.css" rel="stylesheet">
    <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

    <!-- Theme Styles -->
    <link href="../../assets/css/lime.min.css" rel="stylesheet">
    <link href="../../assets/css/custom.css" rel="stylesheet">
    <link href="../../assets/css/upload.css" rel="stylesheet">

    <script src="../../assets/js/valid_busstat.js"></script>
    <script src="https://unpkg.com/cropperjs"></script>





</head>

<body>
    <div class='loader'>
        <div class='spinner-grow text-primary' role='status'>
            <span class='sr-only'>Loading...</span>
        </div>
    </div>

    @include('partials.sidebar')


    <div class="lime-header">
        <nav class="navbar navbar-expand-lg">
            <section class="material-design-hamburger navigation-toggle">
                <a href="javascript:void(0)" class="button-collapse material-design-hamburger__icon">
                    <span class="material-design-hamburger__layer"></span>
                </a>
            </section>
            <a class="navbar-brand"
                href="{{ Auth::check()
                    ? (Auth::user()->hasRole('Root')
                        ? route('upts.index')
                        : (Auth::user()->hasRole('Admin')
                            ? route('dashboard_admin')
                            : (Auth::user()->hasRole('PO')
                                ? route('dashboard_po')
                                : (Auth::user()->hasRole('Upt')
                                    ? route('dashboard_upt')
                                    : route('dashboard')))))
                    : route('login') }}">
                {{ config('app.name', 'TransGo') }}
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="material-icons">keyboard_arrow_down</i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    @if (!Auth::user()->hasRole('Root'))
                        <li class="nav-item">
                            <div class="navbar-text ml-auto d-flex align-items-center">
                                <img src="{{ asset('storage/' . Auth::user()->images) }}" alt="Avatar"
                                    class="avatar mr-2">
                                {{ str_word_count(Auth::user()->name) > 1 ? explode(' ', Auth::user()->name)[0] : Auth::user()->name }}
                            </div>
                        </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @if (!Auth::user()->hasRole('Root'))
                                <a class="dropdown-item" href="{{ route('profile') }}">Profile</a>
                                <div class="dropdown-divider"></div>
                            @endif
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Keluar
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>


    <div class="lime-container">
        @yield('container')
    </div>

    <!-- Javascripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../assets/plugins/jquery/jquery-3.1.0.min.js"></script>
    <script src="../../assets/plugins/bootstrap/popper.min.js"></script>
    <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="../../assets/plugins/cropper-master/cropper.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src="https://unpkg.com/leaflet-polylinedecorator/leaflet.polylineDecorator.js"></script>
    <script src="../../assets/plugins/toastr/toastr.min.js"></script>
    <script src="../../assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="../../assets/js/pages/toastr.js"></script>
    <script src="../../assets/js/lime.min.js"></script>
    <script src="../../assets/js/pages/select2.js"></script>
    <script src="../../assets/js/custom.js"></script>
    <script src="../../assets/js/map.js"></script>
    {{-- <script src="../../assets/js/track.js"></script> --}}
    <script src="../../assets/js/search.js"></script>
    <script src="../../assets/js/valid_busstat.js"></script>
    {{-- <script src="../../assets/js/disabled.js"></script> --}}
    <script src="../../assets/js/multi_del.js"></script>
    <script src="../../assets/js/status.js"></script>
    <script src="../../assets/js/upload.js"></script>
    <script src="../../assets/js/select.js"></script>

    @if (Route::currentRouteName() == 'reservations.print')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/print-this@1.15.0/printThis.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
        <script>
            $(document).ready(function() {
                // Generate barcode based on order id
                JsBarcode("#barcodeCanvas", "{{ $reservation->order_id }}", {
                    format: "CODE128",
                    displayValue: true,
                    fontSize: 14,
                    textMargin: 0,
                    width: 2,
                    height: 40,
                });

                // Handle print button click
                $('#printButton').on('click', function() {
                    $('.col-xl-4').printThis({
                        importCSS: true,
                        loadCSS: "../../assets/css/print.css",
                        pageTitle: "",
                        removeInline: false,
                        printContainer: true
                    });
                });
            });
        </script>
    @endif

    @if (Route::currentRouteName() == 'busses.detail')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var busId = {{ $bus->id }}; // Ambil bus ID dari server-side variable

                // Function to fetch coordinates from the server
                function fetchCoordinates() {
                    fetch('/api/bus-coordinates/' + busId)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Fetched coordinates:', data); // Log data untuk debugging

                            if (data) {
                                // Inisialisasi peta dengan koordinat dari database
                                var map = L.map("track_map").setView([data.latitude, data.longitude], 12);

                                // Add OpenStreetMap tile layer to the map
                                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                                    maxZoom: 19,
                                }).addTo(map);

                                // Create a bus icon
                                var busIcon = L.icon({
                                    iconUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png",
                                    iconSize: [25, 41],
                                    iconAnchor: [12, 41],
                                    popupAnchor: [1, -34],
                                    shadowSize: [41, 41],
                                });

                                // Create a marker with the bus icon
                                var marker = L.marker([data.latitude, data.longitude], {
                                    icon: busIcon
                                }).addTo(map);

                                // Function to update marker position
                                function updateMarker() {
                                    fetch('/api/bus-coordinates/' + busId)
                                        .then(response => response.json())
                                        .then(data => {
                                            console.log('Updated coordinates:',
                                                data); // Log data untuk debugging

                                            if (data) {
                                                var newLatLng = L.latLng(data.latitude, data.longitude);
                                                marker.setLatLng(newLatLng);

                                                // Set the map view to the new marker position
                                                map.setView(newLatLng, map.getZoom());
                                            }
                                        })
                                        .catch(error => console.error('Error fetching coordinates:', error));
                                }

                                // Fetch coordinates every 10 seconds
                                setInterval(updateMarker, 10000);
                            }
                        })
                        .catch(error => console.error('Error fetching coordinates:', error));
                }

                // Initial fetch to set the marker position immediately
                fetchCoordinates();
            });
        </script>
    @endif

    @if (Route::currentRouteName() == 'reservations.create')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var phoneInput = document.getElementById('phone_number');
                var nameInput = document.getElementById('name');
                var addressInput = document.getElementById('address');

                var searchByPhoneButton = document.getElementById('search_by_phone');
                var searchByNameButton = document.getElementById('search_by_name');

                if (searchByPhoneButton) {
                    searchByPhoneButton.addEventListener('click', function() {
                        var phoneNumber = phoneInput.value.trim();

                        if (phoneNumber.length === 0) {
                            alert('Masukkan nomor handphone terlebih dahulu');
                            return;
                        }

                        var params = {
                            phone_number: phoneNumber
                        };

                        // Send AJAX request to search for user
                        axios.get('/search-user', {
                                params: params
                            })
                            .then(function(response) {
                                var userData = response.data;
                                console.log('User data:', userData);

                                // If user data found, populate the form fields
                                if (userData) {
                                    nameInput.value = userData.name;
                                    addressInput.value = userData.address;
                                } else {
                                    // If user not found, clear the form fields
                                    nameInput.value = '';
                                    addressInput.value = '';
                                    toastr.info('Data pengguna tidak ditemukan');
                                }
                            })
                            .catch(function(error) {
                                console.error('Error searching user:', error);
                            });
                    });
                }

                if (searchByNameButton) {
                    searchByNameButton.addEventListener('click', function() {
                        var name = nameInput.value.trim();

                        if (name.length === 0) {
                            alert('Masukkan nama terlebih dahulu');
                            return;
                        }

                        var params = {
                            name: name
                        };

                        // Send AJAX request to search for user
                        axios.get('/search-user', {
                                params: params
                            })
                            .then(function(response) {
                                var userData = response.data;
                                console.log('User data:', userData);

                                // If user data found, populate the form fields
                                if (userData) {
                                    phoneInput.value = userData.phone_number;
                                    addressInput.value = userData.address;
                                } else {
                                    // If user not found, clear the form fields
                                    phoneInput.value = '';
                                    addressInput.value = '';
                                    toastr.info('Data pengguna tidak ditemukan');
                                }
                            })
                            .catch(function(error) {
                                console.error('Error searching user:', error);
                            });
                    });
                }
            });
        </script>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var ticketCountInput = document.getElementById('tickets_booked');
                var totalPriceInput = document.getElementById('total_price');
                var availableChairs = {{ $availableChairs }};
                var price = {{ $price }};

                function formatNumber(num) {
                    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }

                ticketCountInput.addEventListener('input', function() {
                    var ticketCount = parseInt(ticketCountInput.value);

                    // Check if ticket count exceeds available chairs
                    if (ticketCount > availableChairs) {
                        ticketCount = availableChairs; // Set ticket count to available chairs
                        ticketCountInput.value = availableChairs; // Update input field value
                    }

                    var totalPrice = ticketCount * price;
                    totalPriceInput.value = formatNumber(totalPrice); // Update the total price field
                });
            });
        </script>
    @endif

    <!-- Pastikan $userRegistrations telah disertakan sebelum script -->
    @if (Route::currentRouteName() == 'dashboard' || Route::currentRouteName() == 'dashboard_po')
        <script src="../../assets/js/dashboard.js"></script>
        <script>
            function filterBusses() {
                let status = document.getElementById('status').value;
                console.log('Selected status: ' + status);

                // Tentukan URL berdasarkan nama rute saat ini
                let url = "{{ Route::currentRouteName() == 'dashboard' ? route('dashboard') : route('dashboard_po') }}";

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        status: status
                    },
                    success: function(data) {
                        $('#busTableBody').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data: ' + error);
                    }
                });
            }
        </script>


        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Data dari controller
                var dates = @json($dates);
                var reservationsCount = @json($reservationsCount);

                // Inisialisasi Chart.js
                var ctx = document.getElementById('reservationsChart').getContext('2d');
                var reservationsChart = new Chart(ctx, {
                    type: 'line', // Menggunakan grafik garis
                    data: {
                        labels: dates, // Label pada sumbu-x (tanggal)
                        datasets: [{
                            label: 'Jumlah Pemesanan',
                            data: reservationsCount, // Data jumlah pemesanan
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: true,
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Tanggal'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Jumlah Pemesanan'
                                },
                                beginAtZero: true
                            }
                        },
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        }
                    }
                });
            });
        </script>
    @endif

    @if (Route::currentRouteName() == 'schedules.index' ||
            Route::currentRouteName() == 'transits.index' ||
            Route::currentRouteName() == 'reservations.index' ||
            Route::currentRouteName() == 'deposits.index')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Fungsi untuk mengekspor data tabel ke Excel
                document.getElementById('exportToExcel').addEventListener('click', function() {
                    // Ambil judul kolom dari tabel 
                    var columns = [];
                    document.querySelectorAll('table th').forEach(function(th) {
                        columns.push(th.innerText);
                    });

                    // Ambil semua baris dari tabel
                    var rows = document.querySelectorAll('table tr');

                    // Buat array kosong untuk menyimpan data
                    var data = [columns]; // Tambahkan judul kolom sebagai baris pertama

                    // Iterasi melalui setiap baris tabel
                    rows.forEach(function(row) {
                        var rowData = [];

                        // Ambil setiap sel dalam baris
                        row.querySelectorAll('td').forEach(function(cell) {
                            // Tambahkan teks sel ke dalam rowData
                            rowData.push(cell.innerText);
                        });

                        // Tambahkan rowData ke dalam data array
                        data.push(rowData);
                    });
                    // Buat workbook baru
                    var wb = XLSX.utils.book_new();
                    // Buat worksheet baru dengan data dari tabel
                    var ws = XLSX.utils.aoa_to_sheet(data);
                    // Tambahkan worksheet ke dalam workbook
                    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
                    // Simpan workbook sebagai file Excel
                    // Ambil nama route saat ini dan gunakan sebagai nama file
                    var currentRouteName = "{{ Route::currentRouteName() }}";
                    var fileName = currentRouteName.split('.')[0] + '.xlsx';

                    // Simpan workbook sebagai file Excel
                    XLSX.writeFile(wb, fileName);
                });
            });
        </script>
    @endif


    @if (Route::currentRouteName() == 'schedules.edit')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function validatePrices() {
                    var minPrice = parseFloat(document.getElementById("min_price").value) || 0;
                    var maxPrice = parseFloat(document.getElementById("max_price").value) || Infinity;
                    var priceInput = document.getElementById("price");
                    var price = parseFloat(priceInput.value);

                    if (price < minPrice) {
                        priceInput.setCustomValidity("Harga tidak boleh kurang dari harga minimum.");
                    } else if (price > maxPrice) {
                        priceInput.setCustomValidity("Harga tidak boleh lebih dari harga maksimum.");
                    } else {
                        priceInput.setCustomValidity(""); // Clear any previous custom validity message
                    }
                }

                var minPriceInput = document.getElementById("min_price");
                var maxPriceInput = document.getElementById("max_price");
                var priceInput = document.getElementById("price");

                // Add event listeners to validate prices on input
                minPriceInput.addEventListener('input', validatePrices);
                maxPriceInput.addEventListener('input', validatePrices);
                priceInput.addEventListener('input', validatePrices);
            });
            @endif
        </script>

        @if (session('message'))
            <script>
                toastr.success("{{ Session::get('message') }}");
            </script>
        @endif
</body>

</html>
