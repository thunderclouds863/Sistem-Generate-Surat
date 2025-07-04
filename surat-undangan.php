<?php
$conn = new mysqli('localhost', 'root', '', 'HMTI_System');
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Query data dari tabel surat_undangan dan status dari tabel surat
$sql = "SELECT su. id, su.nomor_surat, su.bidang_organisasi, s.status
        FROM surat_undangan su
        JOIN surat s ON su.id = s.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Surat Undangan</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    /* Styling Tracking Button */
    .tracking-button {
      display: inline-block;
      padding: 5px 10px;
      border: 1px solid #ddd;
      border-radius: 50%;
      cursor: pointer;
      font-size: 14px;
      background-color: #fff;
      /* Default polos */
      color: #000;
    }

    .tracking-button.dark-gray {
      background-color: #aaa;
      /* Abu-abu gelap */
      color: #fff;
    }

    .tracking-button.red {
      background-color: #f44336;
      /* Merah */
      color: #fff;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      color: #333;
      background-color: #fff;
    }

    header {
      background-color: #fff;
      padding: 20px 50px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 2px solid #ddd;
      position: relative;
    }

    .logo-container {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
    }

    .logo-container img {
      height: 60px;
      text-align: center;
    }

    .action-button {
      display: block;
      margin: 20px auto;
      padding: 15px 30px;
      background-color: #ffd700;
      color: #000;
      text-decoration: none;
      font-weight: bold;
      border-radius: 5px;
      font-size: 18px;
      text-align: center;
      width: 150px;
      border-radius: 30px;
    }

    .action-button:hover {
      background-color: #e6bf00;
    }

    .action-button img {
      vertical-align: middle;
      margin-right: 10px;
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

    /* Hero Section */
    .hero {
      background: url('bg-surat-undangan.png') no-repeat center;
      text-align: center;
      color: #fff;
      padding: 60px 20px;
      font-size: 28px;
      font-weight: bold;
      position: relative;
    }

    .hero img {
      height: 60px;
      position: absolute;
      top: 10px;
    }

    .hero img:first-child {
      left: 20px;
    }

    .hero img:last-child {
      right: 20px;
    }

    .hero h1 {
      margin: 0;
      font-size: 32px;
      text-transform: uppercase;
    }


    /* Search Bar */
    .search-bar {
      text-align: center;
      margin: 20px 0;
    }

    .search-bar label {
      margin-right: 10px;
      font-size: 16px;
    }

    .search-bar input {
      width: 80%;
      max-width: 500px;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }

    /* Table */
    table {
      width: 90%;
      margin: 20px auto;
      border-collapse: collapse;
      text-align: center;
      font-size: 14px;
    }

    table th,
    table td {
      padding: 10px;
      border: 1px solid #ddd;
    }

    table th {
      background-color: #f4f4f4;
      font-weight: bold;
    }

    table td .tracking-button {
      display: inline-block;
      padding: 5px 10px;
      background-color: #eee;
      border: 1px solid #ddd;
      border-radius: 50%;
      cursor: pointer;
      font-size: 14px;
    }

    table td .tracking-button:hover {
      background-color: #ccc;
    }
  </style>

</head>

<body>
  <!-- Header -->
  <header>
    <div class="header-content">
      <div class="logo-container">
        <img src="logo-undip.png" alt="Logo Undip">
      </div>
      <a href="homepage.php" class="back-button">
        <i class="bi bi-box-arrow-left" style="font-size: 25px;"></i> BACK
      </a>
    </div>
  </header>

  <div class="hero">
    <h1>SURAT UNDANGAN</h1>
  </div>

  <a href="form-surat-undangan.php" class="action-button">
    <i class="bi bi-envelope-paper" style="font-size: 25px;"></i>
    BUAT SURAT
  </a>
  <div class="search-bar">
    <label for="search">Search:</label>
    <input type="text" id="search" placeholder="Cari surat...">
  </div>

  <table>
    <thead>
      <tr>
        <th>Nomor Surat</th>
        <th>Bidang</th>
        <th>Tracking Surat</th>
        <th>Status/File</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()):
        // Tentukan kelas button berdasarkan status
        $tracking1_class = ''; // Default polos
        $tracking2_class = ''; // Default polos

        if ($row['status'] == 'Menunggu Persetujuan Sekretaris Himpunan' || $row['status'] == 'Disetujui Sekretaris Himpunan') {
          $tracking1_class = 'dark-gray';
        }
        if ($row['status'] == 'Menunggu Persetujuan Ketua Himpunan' || $row['status'] == 'Disetujui Ketua Himpunan') {
          $tracking1_class = 'dark-gray';
          $tracking2_class = 'dark-gray';
        }
        if ($row['status'] == 'Rejected') {
          $tracking1_class = 'red';
          $tracking2_class = 'red';
        }
        ?>
        <tr>
          <td><?php echo $row['nomor_surat']; ?></td>
          <td><?php echo $row['bidang_organisasi']; ?></td>
          <td>
            <span class="tracking-button <?php echo $tracking1_class; ?>">1</span>
            <span class="tracking-button <?php echo $tracking2_class; ?>">2</span>
          </td>
          <td>
            <?php
            echo htmlspecialchars($row['status']); // Menampilkan status surat

            // Cek jika statusnya 'Disetujui Ketua Himpunan', tampilkan tombol Download
            if ($row['status'] === 'Disetujui Ketua Himpunan') {
              echo "<br><a href='generate_undangan.php?id=" . $row['id'] . "' class='action-button-download'>Download</a>";
            }

            // Jika status sudah Rejected, tidak perlu tampilkan tombol
            if ($row['status'] === 'Rejected') {
            }
            ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

</body>
<script>
  document.getElementById('search').addEventListener('input', function () {
    const query = this.value.toLowerCase(); // Ambil teks pencarian dalam huruf kecil
    const items = document.querySelectorAll('#surat-list li'); // Ambil semua elemen <li>

    items.forEach(item => {
      // Cocokkan teks pencarian dengan teks elemen <li>
      if (item.textContent.toLowerCase().includes(query)) {
        item.style.display = ''; // Tampilkan jika cocok
      } else {
        item.style.display = 'none'; // Sembunyikan jika tidak cocok
      }
    });
  });
</script>

</html>