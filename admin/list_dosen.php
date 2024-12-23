<?php
include '../conn/conn.php';

// Query untuk mengambil data dosen
$query = "SELECT * FROM dosen"; 
$result = mysqli_query($conn, $query);

// Menyimpan hasil query dalam array
$p = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dosen</title>
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
            align-items: flex-start;
            height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 900px;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        a {
            text-decoration: none;
            color: #6c5ce7;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
            font-size: 14px;
        }

        th, td {
            padding: 12px;
        }

        th {
            background-color: #3c3c3c;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        .button-container {
            text-align: left;
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            padding: 5px 15px;
            background-color: #3c3c3c;
            color: white;
            border-radius: 4px;
            font-size: 14px;
            text-align: center;
            margin-right: 10px;
        }

        .button:hover {
            background-color: #800000;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 5px 15px;
            background-color: #3c3c3c;
            color: white;
            border-radius: 4px;
            font-size: 14px;
            text-align: center;
            
        }

        .back-button:hover {
            background-color: #800000;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Daftar Dosen</h1>

        <div class="button-container">
            <a href="akun_dosen.php" class="button">Tambah Dosen</a>
        </div>

        <table>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Password</th>
            </tr>
            <?php 
            $i = 1;
            foreach ($p as $hasil) {
                // Decrypt password (MD5) before display
                $password = md5($hasil['password']); // MD5 used here as requested
            ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $hasil['username']; ?></td>
                    <td><?= $password; ?></td>
                </tr>
            <?php } ?>
        </table>

        <a href="dashboard.php" class="back-button">Kembali ke Dashboard</a>
    </div>
</body>

</html>
