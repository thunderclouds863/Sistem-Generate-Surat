-- Buat database
CREATE DATABASE IF NOT EXISTS HMTI_System;
USE HMTI_System;

-- Tabel untuk User (Anggota, Kahim, dan Sekhim)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Anggota', 'Ketua Himpunan', 'Sekrektaris Himpunan') NOT NULL
);

-- Tabel Surat (Umum)
CREATE TABLE surat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jenis_surat ENUM('Peminjaman', 'Permohonan', 'Undangan') NOT NULL,
    status ENUM('Pending','Menunggu Persetujuan Sekrektaris Himpunan', 'Disetujui Sekrektaris Himpunan', 'Menunggu Persetujuan Ketua Himpunan', 'Disetujui Ketua Himpunan', 'Rejected') DEFAULT 'Pending'
);

-- Tabel untuk Surat Peminjaman
CREATE TABLE surat_peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_surat VARCHAR(50) NOT NULL UNIQUE,
    tanggal DATE NOT NULL,
    nama_kegiatan VARCHAR(100) NOT NULL,
    waktu_mulai TIME NOT NULL,
    waktu_selesai TIME NOT NULL,
    bidang VARCHAR(50),
    isi_peminjaman TEXT,
    instansi VARCHAR(100) NOT NULL,      -- Kolom untuk Instansi
    lokasi_instansi VARCHAR(100) NOT NULL, -- Kolom untuk Lokasi Instansi
    nama_penanggung_jawab VARCHAR(100),
    nim_penanggung_jawab VARCHAR(15),
    jabatanketua_panitia VARCHAR(100),
    tempat VARCHAR(100),
    program_kerja VARCHAR(100),   -- Kolom untuk Program Kerja
    nama_peminjaman VARCHAR(100),  -- Kolom untuk Nama Peminjaman
    nomor_hp VARCHAR(15),          -- Kolom untuk Nomor HP
    FOREIGN KEY (id) REFERENCES surat(id) ON DELETE CASCADE
);

-- Tabel untuk Surat Permohonan dengan penyesuaian format surat
CREATE TABLE surat_permohonan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_surat VARCHAR(50) NOT NULL UNIQUE,
    tanggal DATE NOT NULL,               -- Kolom untuk Tanggal Surat
    nama_tujuan_permohonan VARCHAR(100) NOT NULL,  -- Kolom untuk Nama Tujuan Permohonan
    instansi VARCHAR(100) NOT NULL,      -- Kolom untuk Instansi
    lokasi_instansi VARCHAR(100) NOT NULL, -- Kolom untuk Lokasi Instansi
    nama_kegiatan VARCHAR(100) NOT NULL, -- Kolom untuk Nama Kegiatan
    waktu_mulai TIME NOT NULL,           -- Kolom untuk Waktu Mulai
    waktu_selesai TIME NOT NULL,         -- Kolom untuk Waktu Selesai
    tempat VARCHAR(100),        -- Kolom untuk Tempat
    program_kerja VARCHAR(100), -- Kolom untuk Program Kerja
    isi_permohonan TEXT,        -- Kolom untuk Isi Permohonan
    bidang VARCHAR(50),         -- Kolom untuk Bidang HMTI
    nama_penanggung_jawab VARCHAR(100),  -- Kolom untuk Nama Penanggung Jawab
    nim_penanggung_jawab VARCHAR(15),    -- Kolom untuk NIM Penanggung Jawab
    nomor_hp VARCHAR(15),       -- Kolom untuk Nomor HP
    status TINYINT DEFAULT 0,
    FOREIGN KEY (id) REFERENCES surat(id) ON DELETE CASCADE
);


-- Tabel untuk Surat Undangan
CREATE TABLE surat_undangan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_surat VARCHAR(50) NOT NULL UNIQUE,
    nama_tamu VARCHAR(100) NOT NULL,
    jabatan_tamu VARCHAR(100) NOT NULL,
    nama_kegiatan VARCHAR(100) NOT NULL,
    hari_tanggal DATE NOT NULL,
    waktu_mulai TIME NOT NULL,
    waktu_selesai TIME NOT NULL,
    tempat VARCHAR(100),
    agenda TEXT ,
    bidang_organisasi VARCHAR(50) ,
    nama_penanggung_jawab VARCHAR(100) ,
    nim_penanggung_jawab VARCHAR(15) ,
    status TINYINT DEFAULT 0,
    FOREIGN KEY (id) REFERENCES surat(id) ON DELETE CASCADE
);

-- Tabel untuk Notifikasi
CREATE TABLE notifikasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message TEXT NOT NULL,
    recipient_id INT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabel untuk Reset Password (token SHA-256, single-use, kedaluwarsa 30 menit)
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token_hash VARCHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    used_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token_hash (token_hash),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Data login awal untuk Anggota, Kahim, dan Sekhim
-- Password disimpan dalam bentuk plaintext hanya untuk contoh (gunakan hashing seperti bcrypt untuk produksi)
INSERT INTO users (username, password, role) VALUES
('kahim', 'kahim123', 'Ketua Himpunan'),
('sekhim', 'sekhim123', 'Sekrektaris Himpunan');
