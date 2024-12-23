<?php
session_start();
include '../conn/conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pertemuan_ke = $_POST['pertemuan_ke'];

    // Validasi hanya boleh memilih pertemuan 1 atau 2
    if (!in_array($pertemuan_ke, [1, 2])) {
        echo "Pertemuan tidak valid. Hanya pertemuan 1 dan 2 yang diizinkan.";
        exit();
    }

    // MD5 untuk hashing pertemuan_ke
    $md5_pertemuan_ke = md5($pertemuan_ke);

    // Periksa apakah pertemuan_ke sudah ada di database
    $query = "SELECT id FROM pertemuan WHERE MD5(pertemuan_ke) = '$md5_pertemuan_ke'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pertemuan_id = $row['id'];
    } else {
        // Jika belum ada, tambahkan ke database
        $query = "INSERT INTO pertemuan (pertemuan_ke) VALUES ('$pertemuan_ke')";
        $conn->query($query);
        $pertemuan_id = $conn->insert_id;
    }

    // Redirect ke halaman scan QR dengan pertemuan_id
    header("Location: scan_qr.php?pertemuan_id=$pertemuan_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Pertemuan</title>
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

        /* Kontainer utama untuk form input */
        .form-container {
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

        /* Styling form dan elemen input */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            font-size: 14px;
            color: #555;
        }

        select,
        button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        select:focus,
        button:focus {
            border-color: #6c5ce7;
            outline: none;
        }

        button {
            background-color: #3c3c3c;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #800000;
        }

        /* Tombol kembali */
        .back-link {
            display: block;
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
        }

        .back-link a {
            color: #3c3c3c;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="form-container">
        <h2>Pilih Pertemuan</h2>
        <form method="POST">
            <div class="form-group">
                <label for="pertemuan_ke">Pilih Pertemuan Ke:</label>
                <select name="pertemuan_ke" id="pertemuan_ke" required>
                    <option value="1">Pertemuan 1</option>
                    <option value="2">Pertemuan 2</option>
                </select>
            </div>

            <button type="submit">Lanjut</button>
        </form>

        <div class="back-link">
            <a href="dashboard.php">Kembali ke Dashboard</a>
        </div>
    </div>

</body>

</html>
