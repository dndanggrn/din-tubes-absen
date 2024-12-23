CREATE TABLE dosen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE absensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pertemuan_id INT,
    mahasiswa_id INT,
    waktu_absen DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pertemuan_id) REFERENCES pertemuan(id),
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id)
);

CREATE TABLE pertemuan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pertemuan_ke INT NOT NULL UNIQUE, -- Nomor pertemuan, unik untuk menghindari duplikasi
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP -- Waktu pencatatan pertemuan
);


CREATE TABLE mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL, -- Berisi NIM
    nama VARCHAR(100),
    password VARCHAR(255) -- Untuk menyimpan password hash
);


CREATE TABLE kehadiran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    pertemuan_id INT NOT NULL,
    waktu_absen TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id),
    FOREIGN KEY (pertemuan_id) REFERENCES pertemuan(id)
);


ALTER TABLE kehadiran
ADD COLUMN latitude DOUBLE,
ADD COLUMN longitude DOUBLE;


CREATE TABLE admin(
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL
);