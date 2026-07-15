<?php
session_start();
if (!isset($_SESSION['status'])) { header("location:login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Jadwal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light p-5">

<div class="card p-4 mx-auto shadow" style="max-width: 500px;">
    <h3 class="mb-4">Tambah Jadwal Baru</h3>
    
    <div class="progress mb-3" style="height: 10px;">
        <div id="form-progress" class="progress-bar bg-primary" style="width: 0%;"></div>
    </div>

    <form id="jadwal-form" method="POST" action="proses_simpan.php">
        <div class="mb-3">
            <label class="form-label">Nama Kegiatan</label>
            <input type="text" id="nama_kegiatan" name="nama_kegiatan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Awal</label>
            <input type="date" id="tgl_awal" name="tgl_awal" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Akhir</label>
            <input type="date" id="tgl_akhir" name="tgl_akhir" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Simpan Jadwal</button>
    </form>
</div>

<!-- Pemanggilan script dengan teknik bypass cache -->
<script src="script.js?nocache=<?php echo uniqid(); ?>"></script>

</body>
</html>