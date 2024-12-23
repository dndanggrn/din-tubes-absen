<?php
include '../conn/conn.php'; // Koneksi ke database

// Set header agar browser memahami respons JSON
header('Content-Type: application/json');

// Validasi parameter `pertemuan_id`
if (isset($_GET['pertemuan_id']) && is_numeric($_GET['pertemuan_id'])) {
    $pertemuan_id = intval($_GET['pertemuan_id']); // Konversi ke integer

    // Query langsung tanpa prepare
    $query = "
    SELECT 
        k.latitude, 
        k.longitude, 
        m.username AS nim, 
        m.nama,
        k.waktu_absen
    FROM kehadiran k
    JOIN mahasiswa m ON k.mahasiswa_id = m.id
    WHERE k.pertemuan_id = $pertemuan_id
";


    // Eksekusi query
    $result = $conn->query($query);

    if ($result) {
        // Jika ada data, format ke array
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Mengirim data dalam format JSON
        echo json_encode($data);
    } else {
        // Jika query gagal dijalankan
        echo json_encode([
            "error" => "Gagal memproses permintaan. Silakan coba lagi atau periksa database."
        ]);
    }
} else {
    // Jika parameter `pertemuan_id` tidak ada atau tidak valid
    echo json_encode([
        "error" => "Parameter 'pertemuan_id' diperlukan dan harus berupa angka valid."
    ]);
}
?>