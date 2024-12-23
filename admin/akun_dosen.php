<?php
session_start();
include '../conn/conn.php'; // Koneksi ke database

$message = ''; // Variabel untuk menampilkan pesan error atau sukses

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil nilai dari input form jika sudah di-submit
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $nama = $_POST['username'];
        $password = $_POST['password'];

        // Cek apakah username sudah ada di database
        $query_check = "SELECT * FROM dosen WHERE username = '$nama'";
        $result_check = $conn->query($query_check);

        if ($result_check->num_rows > 0) {
            // Username sudah ada
            $message = "Username sudah terdaftar!";
        } else {
            // Enkripsi password sebelum disimpan
            $hashed_password = md5($password); // Menggunakan MD5 untuk hashing (tidak direkomendasikan)

            // Query untuk menambahkan akun dosen
            $query = "INSERT INTO dosen (username, password) VALUES ('$nama', '$hashed_password')";

            if ($conn->query($query) === TRUE) {
                $message = "Akun dosen berhasil ditambahkan!";
            } else {
                $message = "Gagal menambahkan akun dosen: " . $conn->error;
            }
        }
    } else {
        $message = "Username dan Password harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Akun Dosen</title>
    <style>
        /* Gaya CSS sama seperti sebelumnya */
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
            max-width: 600px;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-size: 14px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input:focus {
            border-color: #6c5ce7;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
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

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #3c3c3c;
            font-size: 16px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .message {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Tambah Akun Dosen</h1>

        <!-- Menampilkan pesan jika ada -->
        <?php if ($message): ?>
            <div class="message">
                <!-- Jika pesan mengandung "berhasil", beri kelas success, jika tidak beri kelas error -->
                <p class="<?php echo (strpos($message, 'berhasil') !== false) ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </p>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Tambah Akun Dosen</button>
        </form>

        <a href="dashboard.php" class="back-link">Kembali ke Dashboard</a>
    </div>

</body>
</html>
