<?php
include '../conn/conn.php'; // Koneksi ke database

// Data yang akan ditambahkan
$username = 'admin'; // Username
$password = 'admin123'; // Password yang ingin di-set

// Hash password menggunakan MD5
$hashed_password = md5($password); // Menggunakan MD5 untuk hash password

// Query untuk menambahkan data admin ke database
$query = "INSERT INTO admin (username, password) VALUES ('$username', '$hashed_password')";

// Eksekusi query
if ($conn->query($query) === TRUE) {
    echo "Akun admin berhasil ditambahkan!";
} else {
    echo "Gagal menambahkan akun admin: " . $conn->error;
}

$conn->close();
?>
