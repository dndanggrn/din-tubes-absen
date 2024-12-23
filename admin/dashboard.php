<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .dashboard-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 350px;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .dashboard-link {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background-color: #3c3c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 0;
            text-align: center;
            text-decoration: none;
        }

        .dashboard-link:hover {
            background-color: #800000;
        }

        .logout-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
        }

        .logout-link a {
            color: #3c3c3c;
            text-decoration: none;
        }

        .logout-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <h1>Selamat datang, Admin</h1>

        <div class="button-container">
            <a href="list_dosen.php" class="dashboard-link">Daftar Dosen</a>
            <a href="akun_dosen.php" class="dashboard-link">Tambah Dosen</a>
            <a href="list_mahasiswa.php" class="dashboard-link">Daftar Mahasiswa</a>
            <a href="akun_mahasiswa.php" class="dashboard-link">Tambah Mahasiswa</a>
        </div>

        <div class="logout-link">
            <a href="../logout.php">Logout</a>
        </div>
    </div>
</body>

</html>
