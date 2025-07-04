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

// Retrieve the Surat ID from the URL and validate it
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $surat_id = $_GET['id'];
} else {
    die("Invalid Surat ID.");
}
?>

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
            padding: 43px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        header {
            background-color: #fff;
            padding: 5px 0px 10px 0px;
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
            bottom: 10%;
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

        h1 {
            color: #1a237e;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 20px;
            font-size: 25px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        label {
            color: #1a237e;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            font-size: 16px;
        }

        input {
            width: 95%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .simpan-button {
            background-color: #ff5722;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            font-size: 14px;
            float: right;
            cursor: pointer;
            margin-top: 0px;
            font-weight: 600;
        }

        .simpan-button:hover {
            background-color: #f4511e;
        }

        #tempat,
        #bidang,
        #ketuaPanitia,
        #nim {
            width: 50%;
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
                <a href="form-surat-undangan.html" class="back-button">
                    <i class="bi bi-box-arrow-left" style="font-size: 25px;"></i> BACK
                </a>
            </div>
        </header>

        <h1>ISI DATA BERIKUT:</h1>

        <form id="dataForm" action="submit-next-form-surat-undangan.php?id=<?php echo $surat_id; ?>" method="POST">
            <div class="form-group">
                <label>Tempat:</label>
                <input type="text" id="tempat" name="tempat">
            </div>

            <div class="form-group">
                <label>Agenda:</label>
                <input type="text" id="agenda" name="agenda" class="agenda-input">
            </div>

            <div class="form-group">
                <label>Bidang:</label>
                <input type="text" id="bidang" name="bidang">
            </div>

            <div class="form-group">
                <label>Ketua Panitia:</label>
                <input type="text" id="ketuaPanitia" name="ketuaPanitia">
            </div>

            <div class="form-group">
                <label>NIM:</label>
                <input type="text" id="nim" name="nim">
            </div>

            <button type="submit" class="simpan-button">SIMPAN</button>
        </form>

    </div>

    <script>
        document.getElementById('dataForm').addEventListener('submit', function (e) {

        });
    </script>
</body>

</html>
