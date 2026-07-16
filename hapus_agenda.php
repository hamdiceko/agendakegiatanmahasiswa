<?php
session_start();

// 1. Proteksi Halaman
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("location:login.php");
    exit;
}

// 2. Koneksi ke Database
$koneksi = mysqli_connect("localhost", "root", "", "db_agenda_mahasiswa");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 3. Ambil parameter ID dari URL
$id_agenda = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';

if (!empty($id_agenda)) {
    // Query hapus data
    $query_hapus = mysqli_query($koneksi, "DELETE FROM agenda WHERE id_agenda = '$id_agenda'");
    
    if ($query_hapus) {
        echo "<script>
                alert('Agenda berhasil dihapus!');
                window.location = 'agenda.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Gagal menghapus agenda: " . mysqli_error($koneksi) . "');
                window.location = 'agenda.php';
              </script>";
        exit;
    }
} else {
    header("location:agenda.php");
    exit;
}
?>