<?php
session_start();
include '../conn/conn.php';
require '../vendor/autoload.php'; // Pastikan Anda menginstal library QR Code

use Endroid\QrCode\Builder\Builder; // Menggunakan Builder untuk membuat QR Code

$qrCodeImage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    
    // Menghasilkan QR Code dengan Builder
    $result = Builder::create()
        ->data($nim)
        ->size(150) // Ukuran QR Code lebih kecil
        ->build();

    // Mengonversi QR Code ke base64 agar dapat ditampilkan sebagai gambar di halaman yang sama
    $qrCodeImage = 'data:image/png;base64,' . base64_encode($result->getString());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat QR Code</title>
    <style>
        /* Reset style */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Background dan tampilan umum halaman */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        /* Kontainer utama halaman */
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        /* Formulir untuk memasukkan NIM */
        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #3c3c3c;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }

        button:hover {
            background-color: #800000;
        }

        /* QR Code hasil */
        .qr-code-container {
            margin-top: 20px;
        }

        .qr-code-container img {
            max-width: 100%;
            height: auto;
        }

        /* Link navigasi */
        a {
            display: inline-block;
            margin-top: 15px;
            color: #3c3c3c;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
            color: #800000;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Buat QR Code</h2>

        <form method="POST">
            <label for="nim">NIM:</label>
            <input type="text" name="nim" id="nim" required>
            <button type="submit">Buat QR Code</button>
        </form>

        <?php if ($qrCodeImage): ?>
            <div class="qr-code-container">
                <h3>QR Code Anda:</h3>
                <img src="<?php echo $qrCodeImage; ?>" alt="QR Code">
            </div>
        <?php endif; ?>

        <a href="dashboard.php">Kembali</a><br>
        <a href="../logout.php">Logout</a>
    </div>

</body>
</html>
