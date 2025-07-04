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


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve'])) {
        $status = 'Disetujui Ketua Himpunan'; // Sesuaikan dengan ENUM Anda
    } elseif (isset($_POST['reject'])) {
        $status = 'Rejected'; // Sesuaikan dengan ENUM Anda
    }

    if (isset($status)) {
        $id = $_POST['id'];

        $sql = "UPDATE surat SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);

        if ($stmt->execute()) {
            header("Location: surat-undangan-kahim.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}


$conn->close();
?>
