<?php
session_start();
include 'conn/conn.php';

$error_message = ''; // Variable untuk menampung pesan error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Pilih tabel sesuai peran
    if ($role == 'admin') {
        $query = "SELECT * FROM admin WHERE username = '$username'";
    } elseif ($role == 'mahasiswa') {
        $query = "SELECT * FROM mahasiswa WHERE username = '$username'";
    } elseif ($role == 'dosen') {
        $query = "SELECT * FROM dosen WHERE username = '$username'";
    }

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verifikasi password menggunakan MD5
        if (md5($password) == $user['password']) {
            // Set sesi pengguna
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $role;

            // Redirect berdasarkan peran
            if ($role == 'admin') {
                header("Location: admin/dashboard.php");
            } elseif ($role == 'mahasiswa') {
                header("Location: mahasiswa/dashboard.php");
            } elseif ($role == 'dosen') {
                header("Location: dosen/dashboard.php");
            }
            exit();
        } else {
            $error_message = "Password salah!";
        }
    } else {
        $error_message = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        }

        .login-container {
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

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            font-size: 14px;
            color: #555;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input:focus,
        select:focus {
            border-color: #964b00;
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

        /* Styling untuk pesan error */
        .error-message {
            color: red;
            margin-bottom: 15px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>

        <!-- Menampilkan pesan error jika ada -->
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" required>
                    <option value="admin">Admin</option>
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="dosen">Dosen</option>
                </select>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>
