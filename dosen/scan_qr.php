<?php
session_start();
include '../conn/conn.php'; // Koneksi ke database

// Ambil `pertemuan_id` dari query string
if (!isset($_GET['pertemuan_id']) || !is_numeric($_GET['pertemuan_id'])) {
    http_response_code(400);
    echo "ID pertemuan tidak valid!";
    exit();
}
$pertemuan_id = intval($_GET['pertemuan_id']);

// Jika form untuk pemindaian QR dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = isset($_POST['nim']) ? trim($_POST['nim']) : null;
    $latitude = isset($_POST['latitude']) ? floatval($_POST['latitude']) : null;
    $longitude = isset($_POST['longitude']) ? floatval($_POST['longitude']) : null;

    // Validasi input
    if (empty($nim) || is_null($latitude) || is_null($longitude)) {
        http_response_code(400);
        echo "Data tidak lengkap. Pastikan semua data (NIM, latitude, longitude) diisi.";
        exit();
    }

    // Validasi lokasi (cek jarak ke kampus)
    $kampus_lat = -2.7370586; // Latitude kampus
    $kampus_lon = 107.6531581; // Longitude kampus
    $distance = haversineGreatCircleDistance($latitude, $longitude, $kampus_lat, $kampus_lon);

    if ($distance > 1000) { // Jarak lebih dari 1 km
        http_response_code(403);
        echo "Absen gagal! Anda berada di luar radius kampus.";
        exit();
    }

    // Cek apakah NIM ada di tabel mahasiswa
    $query_check = "SELECT id FROM mahasiswa WHERE username = ?";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bind_param("s", $nim);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $row = $result_check->fetch_assoc();
        $mahasiswa_id = $row['id'];

        // Cek apakah mahasiswa sudah absen untuk pertemuan ini
        $query_duplicate = "SELECT id FROM kehadiran WHERE mahasiswa_id = ? AND pertemuan_id = ?";
        $stmt_duplicate = $conn->prepare($query_duplicate);
        $stmt_duplicate->bind_param("ii", $mahasiswa_id, $pertemuan_id);
        $stmt_duplicate->execute();
        $result_duplicate = $stmt_duplicate->get_result();

        if ($result_duplicate->num_rows > 0) {
            http_response_code(409);
            echo "Mahasiswa dengan NIM $nim sudah absen untuk pertemuan ini.";
        } else {
            // Simpan data kehadiran, termasuk lokasi
            $query_insert = "INSERT INTO kehadiran (mahasiswa_id, pertemuan_id, latitude, longitude) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($query_insert);
            $stmt_insert->bind_param("iidd", $mahasiswa_id, $pertemuan_id, $latitude, $longitude);

            if ($stmt_insert->execute()) {
                http_response_code(200);
                echo "Kehadiran mahasiswa dengan NIM $nim berhasil disimpan!";
            } else {
                http_response_code(500);
                echo "Gagal menyimpan kehadiran mahasiswa.";
            }
        }
    } else {
        http_response_code(404);
        echo "Mahasiswa dengan NIM $nim tidak ditemukan!";
    }
    exit();
}

// Fungsi untuk menghitung jarak menggunakan formula Haversine
function haversineGreatCircleDistance($lat1, $lon1, $lat2, $lon2, $earthRadius = 6371000)
{
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    $deltaLat = $lat2 - $lat1;
    $deltaLon = $lon2 - $lon1;

    $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
        cos($lat1) * cos($lat2) *
        sin($deltaLon / 2) * sin($deltaLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c; // Hasil dalam meter
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code Mahasiswa</title>
    <!-- Library QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode/html5-qrcode.min.js"></script>
    <!-- Leaflet Map CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body {
            margin: 0;
            /* Hilangkan margin default */
            padding: 50px;
            /* Tambahkan jarak antara konten dan tepi halaman */
            box-sizing: border-box;
            /* Pastikan padding dihitung dalam ukuran elemen */
            background-color: #f4f7fc;
            /* Opsional: Beri warna latar untuk halaman */
        }

        /* tinggi batas spasi antara tempat scan qr code dengan elemen di bawahnya*/
        #scan-container {
            height: 550px;
        }

        /* Style untuk kolom daftar kehadiran */
        #attendance-container {
            /* lebar box daftar kehadiran */
            width: 90%;
            /*background box daftar kehadirannya lebih gelap dari background halaman*/
            background-color: #e8ebf1; 
            color: black;
            font-size: 14px;
            /* Ubah rata teks jika diperlukan */
            /* text-align: left; */
            /* Kurangi padding (jarak isi dengan tepi box) agar lebih pas */
            padding: 20px;
            /* box-sizing: border-box; */
            /* jarak antara box dengan elemen diatas dan dibawahnya, serta tepi halaman: rata tengah (auto)*/
            margin: 20px auto;
            margin-bottom: 120px;
            /* lengkungan di sudut box */
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.4);
        }


        #notification {
            display: none;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
        }

        /* #map {
            height: 500px;
            width: 50%;
            margin-top: 20px;
        } */
        #map {
            height: 200px;
            /* width gunanya untuk melebarkan maps */
            width: 90%;
            /* Auto di kiri dan kanan memusatkan elemen secara horizontal */
            margin: 20px auto;
            /* Memastikan elemen dianggap sebagai block */
            display: block;
        }

        #qr-reader {
            width: 100%;
            margin: 20px auto;
            max-width: 500px;
        }


        h2,
        h1,
        p {
            text-align: center;
        }

        /* Style daftar kehadiran */
        .attendance-item {
            font-size: 18px;
            /* Ukuran huruf lebih besar */
            line-height: 1.8;
            /* Spasi antar baris */
            margin-bottom: 10px;
            /* Spasi antar daftar */
        }

        .lokasi {
            background-color: black;
            border-radius: 5px;
            color: white;
            padding: 10px;
            /* padding untuk jarak antar tepi tombol ke teks */
        }

        .button-container {
            height: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        a {
            display: block;
            margin: 6px 0;
            padding: 12px;
            background-color: #3c3c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            /* Pastikan teks berada di tengah */
            width: 80px;
            /* width untuk lebar tombol yang sama */
            
        }

        a:hover {
            background-color: #800000;
        }
    </style>
</head>

<body>
    <div id="scan-container">
        <h2><u>Scan QR Code Mahasiswa</u></h2>
        <p>Pertemuan Ke: <?php echo htmlspecialchars($pertemuan_id ?? 'Tidak Ada ID'); ?></p>

        <!-- Tempat untuk scanner QR code -->
        <div id="qr-reader"></div>
        <div id="notification"></div>
        <br>
    </div>

    <!-- Tempat untuk daftar mahasiswa yang sudah absen -->
    <div id="attendance-container">
        <h1>Daftar Kehadiran</h1>
        <hr>
        <div id="absentees-list">
        </div>
    </div>


    <!-- Tempat untuk menampilkan peta lokasi mahasiswa -->
    <h2>Peta Kehadiran</h2>
    <div id="map"></div>

    <script>
        const pertemuanId = "<?php echo $pertemuan_id ?? ''; ?>";
        let map, markers = [];

        if (!pertemuanId) {
            alert("ID pertemuan tidak ditemukan!");
            throw new Error("ID pertemuan tidak ditemukan!");
        }

        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, isError = false) {
            const notification = document.getElementById('notification');
            notification.style.color = isError ? 'red' : 'green';
            notification.innerText = message;
            notification.style.display = 'block';
            setTimeout(() => notification.style.display = 'none', 5000);
        }

        // Fungsi untuk memuat daftar mahasiswa yang sudah absen
        // function loadAttendanceList() {
        //     const xhr = new XMLHttpRequest();
        //     xhr.open("GET", `get_kehadiran.php?pertemuan_id=${pertemuanId}`, true);
        //     xhr.onload = function () {
        //         if (xhr.status === 200) {
        //             const data = JSON.parse(xhr.responseText);
        //             let output = '';
        //             data.forEach(function (item) {
        //                 output += `<p>${item.nim} - ${item.nama} 
        //                 <button onclick="viewLocation(${item.latitude}, ${item.longitude}, '${item.nim} - ${item.nama}')">Lihat Lokasi</button></p>`;
        //             });
        //             document.getElementById("absentees-list").innerHTML = output;
        //             displayLocations(data);
        //         }
        //     };
        //     xhr.send();
        // }

        function loadAttendanceList() {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", `get_kehadiran.php?pertemuan_id=${pertemuanId}`, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const data = JSON.parse(xhr.responseText);
                    let output = '';
                    data.forEach(function (item) {
                        output += `
                    <p class="attendance-item">
                        ${item.nim} - ${item.nama} 
                        <br>Waktu Absen: ${item.waktu_absen}
                        <br>
                        <button class="lokasi" onclick="viewLocation(${item.latitude}, ${item.longitude}, '${item.nim} - ${item.nama}')">
                            Lihat Lokasi
                        </button>
                    </p>
                `;
                    });
                    document.getElementById("absentees-list").innerHTML = output;
                    displayLocations(data);
                }
            };
            xhr.send();
        }


        // Fungsi untuk menampilkan lokasi mahasiswa di peta
        function displayLocations(data) {
            if (!map) {
                map = L.map('map').setView([-2.7370586, 107.6531581], 13); // Lokasi kampus
                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);
            }

            // Hapus marker lama
            markers.forEach(marker => map.removeLayer(marker));
            markers = [];

            // Tambahkan marker baru
            data.forEach(function (item) {
                const lat = parseFloat(item.latitude);
                const lon = parseFloat(item.longitude);
                const marker = L.marker([lat, lon]).addTo(map);
                marker.bindPopup(`<b>${item.nim} - ${item.nama}</b>`);
                markers.push(marker);
            });
        }

        // Fungsi untuk melihat lokasi mahasiswa di peta
        function viewLocation(latitude, longitude, studentName) {
            map.setView([latitude, longitude], 15);
            L.marker([latitude, longitude]).addTo(map)
                .bindPopup(`<b>${studentName}</b>`)
                .openPopup();
        }

        // Fungsi untuk mendapatkan lokasi dan mengirim data ke server
        function getLocationAndSubmit(nim) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", `scan_qr.php?pertemuan_id=${pertemuanId}`, true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            showNotification(xhr.responseText, false);
                        } else {
                            showNotification(xhr.responseText, true);
                        }
                        // Muat ulang daftar kehadiran
                        loadAttendanceList();
                    };
                    xhr.send(`nim=${nim}&latitude=${latitude}&longitude=${longitude}`);
                }, function (error) {
                    showNotification("Gagal mendapatkan lokasi: " + error.message, true);
                });
            } else {
                showNotification("Geolocation tidak didukung oleh browser Anda.", true);
            }
        }

        // Fungsi saat QR code berhasil dipindai
        function onScanSuccess(decodedText) {
            getLocationAndSubmit(decodedText);
        }

        // Memulai scanner QR code
        const html5QrCode = new Html5Qrcode("qr-reader");
        html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: 250 }, onScanSuccess);

        // Muat daftar kehadiran saat halaman pertama kali dimuat
        loadAttendanceList();
    </script>

    <br>
    <div class="button-container">
        <a href="input_pertemuan.php">Kembali</a>
        <a href="../logout.php">Logout</a>
    </div>

</body>
<!-- <a href="dashboard.php" class="button back-btn">Kembali</a>
<a href="../logout.php" class="button logout-btn">Logout</a> -->

</html>