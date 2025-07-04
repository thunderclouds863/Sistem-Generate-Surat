
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Data Kegiatan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: 10%;
        }

        .logo {
            width: 60px;
            height: auto;
        }

        .back-button {
            background-color: #27224d;
            color: #fff;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
        }

        .back-button:hover {
            background-color: #1b2f54;
        }

        h1 {
            color: #1a237e;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .date-selects {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        select,
        input {
            width: 95%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
        }

        .horizontal-group {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: center;
        }

        .horizontal-group .form-item {
            flex: 1;
        }

        .horizontal-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #1a237e;
        }

        .horizontal-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .col {
            flex: 1;
        }

        label {
            color: #1a237e;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .next-button {
            background-color: #ff5722;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            float: right;
            cursor: pointer;

        }

        header {
            background-color: #fff;
            padding: 20px 0px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #ddd;
            position: relative;
        }

        #tanggal, #bulan, #tahun{
            width:13%
        }

        #nomor, #programKerja, #bulanRomawi {
            width: 5%;
        }

        #tahunInput {
            width: 18%;
        }

    </style>
</head>

<body>
    <div class="container">
        <header>
            <div class="header-content">
                <div class="logo-container">
                    <img src="logo-undip.png" alt="Logo Undip">
                </div>
                <a href="surat-permohonan.html" class="back-button">
                    <i class="bi bi-box-arrow-left" style="font-size: 25px;"></i> BACK
                </a>
            </div>
        </header>

        <h1>ISI DATA BERIKUT:</h1>

        <form id="dataForm" action="submit-form-surat-permohonan.php" method="POST">
        <div class="date-selects">
                <select id="tanggal" name="tanggal">
                    <option value="">Tanggal</option>
                    <!-- JS will populate this -->
                </select>
                <select id="bulan" name="bulan">
                    <option value="">Bulan</option>
                    <!-- JS will populate this -->
                </select>
                <select id="tahun" name="tahun">
                    <option value="">Tahun</option>
                    <!-- JS will populate this -->
                </select>
            </div>

            <!-- Nomor Surat Group -->
            <label>Nomor Surat:</label>
            <div class="form-group horizontal-group">
                    <label>Nomor:</label>
                    <input type="text" id="nomor" name="nomor">
                    <label>Program Kerja:</label>
                    <input type="text" id="programKerja" name="programKerja">
                    <label>Bulan Romawi:</label>
                    <input type="text" id="bulanRomawi" name="bulanRomawi">
                    <label>Tahun:</label>
                    <input type="text" id="tahunInput" name="tahunInput">
            </div>

            <!-- Other Fields -->
            <div class="row">
                <div class="col">
                    <label>Nama Tujuan Permohonan:</label>
                    <input type="text" id="tujuan" name="tujuan">
                </div>
                <div class="col">
                    <label>Nama Kegiatan:</label>
                    <input type="text" id="kegiatan" name="kegiatan">
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label>Instansi:</label>
                    <input type="text" id="instansi" name="instansi">
                </div>
                <div class="col">
                    <label>Waktu Mulai:</label>
                    <input type="time" id="waktuMulai" name="waktuMulai">
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <label>Lokasi Instansi:</label>
                    <input type="text" id="lokasiTamu" name="lokasiTamu">
                </div>
                <div class="col">
                    <label>Waktu Selesai:</label>
                    <input type="time" id="waktuSelesai" name="waktuSelesai">
                </div>
            </div>

            <button type="submit" class="next-button">NEXT</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Populate date dropdowns
            const tanggalSelect = document.getElementById('tanggal');
            const bulanSelect = document.getElementById('bulan');
            const tahunSelect = document.getElementById('tahun');

            for (let i = 1; i <= 31; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                tanggalSelect.appendChild(option);
            }

            const bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            bulanList.forEach((bulan, index) => {
                const option = document.createElement('option');
                option.value = index + 1;
                option.textContent = bulan;
                bulanSelect.appendChild(option);
            });

            const currentYear = new Date().getFullYear();
            for (let i = currentYear - 5; i <= currentYear + 5; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                tahunSelect.appendChild(option);
            }
        });
    </script>
</body>

</html>

