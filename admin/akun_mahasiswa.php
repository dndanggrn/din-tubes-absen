<?php
session_start();
include '../conn/conn.php'; // Koneksi ke database

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['username']; // Ambil NIM dari input username
    $nama = $_POST['nama'];
    $password = $_POST['password'];

    // Enkripsi password menggunakan MD5 (tidak direkomendasikan untuk keamanan)
    $hashed_password = md5($password);

    // Query untuk menambahkan akun mahasiswa
    $query = "INSERT INTO mahasiswa (username, nama, password) VALUES ('$nim', '$nama', '$hashed_password')";

    if (mysqli_query($conn, $query)) {
        $message = "Akun mahasiswa berhasil ditambahkan!";
    } else {
        $message = "Gagal menambahkan akun mahasiswa! Username sudah digunakan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Akun Mahasiswa</title>
    <style>
        /* General styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input:focus {
            border-color: #6c5ce7;
            outline: none;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #3c3c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #800000;
        }

        .message {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
            padding: 10px;
            border-radius: 4px;
        }

        .message.success {
            color: #2e7d32;
        }

        .message.error {
            color: #c62828;
        }

        .back-link {
            display: block;
            margin-top: 5px;
            text-align: center;
        }

        .back-link a {
            text-decoration: none;
            color: #3c3c3c;
            padding: 10px;
            border-radius: 4px;
            display: inline-block;
            width: 100%;
            text-align: left;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Tambah Akun Mahasiswa</h2>
        <?php if ($message): ?>
            <div class="message <?php echo (strpos($message, 'berhasil') !== false) ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">NIM</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Tambah Akun Mahasiswa</button>
        </form>

        <div class="back-link">
            <a href="dashboard.php">Kembali ke Dashboard</a>
        </div>
    </div>
</body>

</html>
