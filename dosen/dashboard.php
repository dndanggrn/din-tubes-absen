<?php
session_start();

// Mengecek apakah sesi sudah ada dan apakah peran pengguna adalah dosen
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'dosen') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
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

        /* Kontainer utama dashboard */
        .dashboard-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        /* Link dan tombol pada dashboard */
        a {
            display: block;
            margin: 15px 0;
            padding: 12px;
            background-color: #3c3c3c;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
        }

        a:hover {
            background-color: #800000;
        }

        /* Pesan selamat datang */
        .welcome-message {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }

        /* Tombol logout */
        .logout-button {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <h2>Dashboard Dosen</h2>
        <div class="welcome-message">
            <?php echo "Selamat datang Dosen, " . $_SESSION['username']; ?>
        </div>
        
        <a href="input_pertemuan.php">Absensi</a>
        <a href="../logout.php">Logout</a>
    </div>

</body>
</html>
