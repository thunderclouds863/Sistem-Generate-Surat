-- Akun demo: anggota1/demo123 (login.php). Kahim/Sekhim bawaan hmti_system.sql: kahim/kahim123, sekhim/sekhim123 (login_role.php)
USE HMTI_System;
INSERT INTO users (username, password, role) VALUES ('anggota1', '$2y$10$52hboDnXS8GD5GyOOao2AO6mH7qI2M0ROpfd4EL8tO.W5hBADvWv6', 'Anggota');
INSERT INTO surat (id, jenis_surat, status) VALUES
(1, 'Peminjaman', 'Disetujui Ketua Himpunan'),
(2, 'Peminjaman', 'Menunggu Persetujuan Sekrektaris Himpunan'),
(3, 'Permohonan', 'Disetujui Ketua Himpunan'),
(4, 'Undangan', 'Disetujui Ketua Himpunan');
INSERT INTO surat_peminjaman (id, nomor_surat, tanggal, nama_kegiatan, waktu_mulai, waktu_selesai, bidang, isi_peminjaman, instansi, lokasi_instansi, nama_penanggung_jawab, nim_penanggung_jawab, tempat, program_kerja, nama_peminjaman, nomor_hp) VALUES
(1, '001/HMTI/XI/2026', CURDATE() - INTERVAL 3 DAY, 'Seminar Industri 4.0', '08:00', '12:00', 'Keilmuan', 'Proyektor dan sound system', 'Departemen Teknik Industri', 'Tembalang', 'Penanggung Jawab Demo', '21070000000001', 'Ruang Seminar', 'Seminar Tahunan', 'Proyektor', '081200000001'),
(2, '002/HMTI/XI/2026', CURDATE() + INTERVAL 7 DAY, 'Workshop Lean Manufacturing', '13:00', '16:00', 'Keilmuan', 'Ruang kelas dan meja', 'Departemen Teknik Industri', 'Tembalang', 'Penanggung Jawab Demo', '21070000000001', 'Gedung Kuliah', 'Workshop Semester', 'Ruang kelas', '081200000001');
INSERT INTO surat_permohonan (id, nomor_surat, tanggal, nama_tujuan_permohonan, instansi, lokasi_instansi, nama_kegiatan, waktu_mulai, waktu_selesai, tempat, program_kerja, isi_permohonan, bidang, nama_penanggung_jawab, nim_penanggung_jawab, nomor_hp, status) VALUES
(3, '003/HMTI/XI/2026', CURDATE() - INTERVAL 2 DAY, 'Ketua Departemen Teknik Industri', 'Universitas Diponegoro', 'Tembalang, Semarang', 'Kuliah Umum Supply Chain 4.0', '09:00', '12:00', 'Auditorium Gedung A', 'Kuliah Umum Semester', 'Permohonan menjadi pembicara utama pada Kuliah Umum Supply Chain 4.0', 'Keilmuan', 'Penanggung Jawab Demo', '21070000000001', '081200000001', 1);
INSERT INTO surat_undangan (id, nomor_surat, nama_tamu, jabatan_tamu, nama_kegiatan, hari_tanggal, waktu_mulai, waktu_selesai, tempat, agenda, bidang_organisasi, nama_penanggung_jawab, nim_penanggung_jawab, status) VALUES
(4, '004/HMTI/XI/2026', 'Dr. Ir. Budi Santoso, M.T.', 'Kepala Laboratorium Sistem Produksi', 'Rapat Koordinasi Tahunan HMTI', CURDATE() + INTERVAL 5 DAY, '10:00', '12:00', 'Ruang Rapat HMTI', 'Pembahasan kalender program kerja semester genap', 'Organisasi', 'Penanggung Jawab Demo', '21070000000001', 1);
