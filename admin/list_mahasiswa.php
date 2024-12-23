<?php
include '../conn/conn.php';

$query = "SELECT * FROM mahasiswa"; // Menampilkan semua data dari tabel mahasiswa
$result = mysqli_query($conn, $query);
$p = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa</title>
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
            max-width: 800px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 14px;
        }

        table th {
            background-color: #3c3c3c;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .btn {
            padding: 5px 15px;
            text-decoration: none;
            color: white;
            background-color: #3c3c3c;
            border-radius: 4px;
            text-align: center;
            display: inline-block;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #800000;
        }

        .back-btn {
            margin-top: 25px;
            font-size: 14px;
        }

        .back-btn a {
            text-decoration: none;
            color: white;
            background-color: #3c3c3c;
            padding: 5px 15px;
            border-radius: 4px;
        }

        .back-btn a:hover {
            background-color: #800000;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Daftar Mahasiswa</h2>
        <div class="btn-container">
            <a href="akun_mahasiswa.php" class="btn">Tambah Mahasiswa</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Password</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                foreach ($p as $hasil) {
                    // Menggunakan MD5 untuk mengenkripsi password
                    $encryptedPassword = md5($hasil['password']);
                ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $hasil['username']; ?></td>
                    <td><?= $hasil['nama']; ?></td>
                    <td><?= $encryptedPassword; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="back-btn">
            <a href="dashboard.php">Kembali ke Dashboard</a>
        </div>
    </div>
</body>

</html>
