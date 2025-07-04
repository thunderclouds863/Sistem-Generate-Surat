<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "HMTI_System";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$sql = "SELECT sp.id, sp.nomor_surat, s.status FROM surat_peminjaman sp
        JOIN surat s ON sp.id = s.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Surat Peminjaman</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
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
      background: url('bg-surat-peminjaman.png') no-repeat center;
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

    button {
      padding: 8px 12px;
      border: none;
      border-radius: 5px;
      font-size: 14px;
      cursor: pointer;
    }

    button:hover {
      opacity: 0.8;
    }

    .btn-success {
      background-color: #28a745;
      color: #fff;
    }

    .btn-danger {
      background-color: #dc3545;
      color: #fff;
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
    <h1>SURAT PEMINJAMAN</h1>
  </div>
  <div class="search-bar">
    <label for="search">Search:</label>
    <input type="text" id="search" placeholder="Cari surat...">
  </div>

  <table>
  <thead>
    <tr>
      <th>Nomor Surat</th>
      <th>Surat</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // Query untuk mengambil data dan status dari tabel surat
    $sql = "SELECT sp.id, sp.nomor_surat, s.status FROM surat_peminjaman sp
            JOIN surat s ON sp.id = s.id";
    $result = $conn->query($sql);

    // Tampilkan data
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['nomor_surat'] . "</td>";
        echo "<td>";
        echo "<a href='generate_peminjaman.php?id=" . $row['id'] . "' class='action-button'>Download</a>";
        echo "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "<td>";
        // Menampilkan tombol Approve dan Reject jika status masih Pending
        if ($row['status'] == 'Disetujui Sekrektaris Himpunan') {
          echo "<form action='update-status-kahim.php' method='POST' style='display:inline-block;'>
                <input type='hidden' name='id' value='" . $row['id'] . "' />
                <button type='submit' name='approve' class='btn btn-success'>Approve</button>
              </form>";
          echo "<form action='update-status-kahim.php' method='POST' style='display:inline-block;'>
                <input type='hidden' name='id' value='" . $row['id'] . "' />
                <button type='submit' name='reject' class='btn btn-danger'>Reject</button>
              </form>";
        }
        echo "</td>";
        echo "</tr>";
      }
    } else {
      echo "<tr><td colspan='4'>No data available</td></tr>";
    }
    ?>
  </tbody>
</table>

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
</body>

</html>