<?php
session_start();
if(isset($_GET['logout'])) {
    session_destroy();
    header("location:login.php");
    exit;
}

// 1. Proteksi Halaman
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("location:login.php");
    exit;
}

// 2. Koneksi ke Database
$host = "localhost"; 
$user = "root";      
$pass = "";          
$db   = "db_agenda_mahasiswa"; 

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 3. Ambil data dari tabel agenda
$query_tampil = mysqli_query($koneksi, "SELECT * FROM agenda ORDER BY id_agenda DESC");

// 4. Hitung total agenda secara dinamis
$total_agenda = mysqli_num_rows($query_tampil);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Agenda Organisasi Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar p-3 d-none d-md-block">
            <div class="d-flex align-items-center mb-4 px-2">
                <span class="fs-5 fw-bold tracking-wide">SI-AGENDA</span>
            </div>
            <hr class="text-secondary">
            <ul class="nav flex-column gap-2">
                <li class="nav-item"><a href="home.php" class="nav-link py-2.5 px-3">Dashboard Agenda</a></li>
                <li class="nav-item"><a href="jadwal.php" class="nav-link py-2.5 px-3">Jadwal Agenda</a></li>
                <li class="nav-item"><a href="agenda.php" class="nav-link active py-2.5 px-3">List Agenda</a></li>
                <li class="nav-item"><a href="pengurus.php" class="nav-link py-2.5 px-3">Data Pengurus</a></li>
                <li class="nav-item"><a href="?logout=true" class="nav-link py-2.5 px-3 text-danger mt-5" onclick="return confirm('Apakah Anda yakin ingin keluar?')">Keluar</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h1 class="h2 fw-bold text-dark">Sistem Agenda Kegiatan</h1>
                    <p class="text-secondary small">Kelola jadwal dan kegiatan organisasi mahasiswa dengan mudah.</p>
                </div>
            </div>

            <div class="card card-custom bg-white shadow-sm border-0 rounded-3">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0 text-dark">Jadwal & Agenda Aktif</h5>
                    <!-- Badge Otomatis Di Sini -->
                    <span class="badge bg-secondary"><?= $total_agenda; ?> Agenda Terdaftar</span>
                </div>
                <div class="card-body p-0 mt-2">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary small">
                                <tr>
                                    <th class="ps-4">NAMA KEGIATAN</th>
                                    <th>WAKTU PELAKSANAAN</th>
                                    <th>TEMPAT</th>
                                    <th>STATUS</th>
                                    <th class="text-end pe-4">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($data = mysqli_fetch_array($query_tampil)) { ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($data['nama_kegiatan']); ?></div>
                                        <div class="text-muted small"><?= htmlspecialchars($data['deskripsi']); ?></div>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold text-dark"><?= date('d M Y', strtotime($data['tanggal_mulai'])); ?></div>
                                        <div class="text-muted small"><?= date('H:i', strtotime($data['tanggal_mulai'])); ?> - <?= date('H:i', strtotime($data['tanggal_selesai'])); ?> WIB</div>
                                    </td>
                                    <td><span class="text-dark small"><?= htmlspecialchars($data['tempat']); ?></span></td>
                                    <td>
                                        <?php 
                                        $warna = ($data['status'] == 'Mendatang') ? 'bg-warning text-dark' : (($data['status'] == 'Berlangsung') ? 'bg-success text-white' : 'bg-secondary text-white');
                                        ?>
                                        <span class="badge <?= $warna; ?>"><?= $data['status']; ?></span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <a href="edit_agenda.php?id=<?= $data['id_agenda']; ?>" class="btn btn-outline-primary">Edit</a>
                                            <a href="hapus_agenda.php?id=<?= $data['id_agenda']; ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus agenda ini?')">Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>