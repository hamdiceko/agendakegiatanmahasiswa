<?php
session_start();

// 1. Proteksi Halaman
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("location:login.php");
    exit;
}

// 2. Koneksi ke Database
$host = "localhost"; 
$user = "root";      
$pass = "";          
$db   = "agendakegiatanmahasiswa"; 
$db   = ""; 

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 3. Logika Menangkap & Menyimpan Data Form
if (isset($_POST['simpan'])) {
    $nama_kegiatan      = $_POST['nama_kegiatan'];
    $tempat_pelaksanaan = $_POST['tempat'];          
    $tanggal_mulai      = $_POST['tanggal_mulai'];
    $tanggal_selesai    = $_POST['tanggal_selesai'];
    $status_awal        = $_POST['status'];          
    $deskripsi_kegiatan = $_POST['deskripsi'];       

    // Query INSERT yang sudah disesuaikan dengan nama kolom di phpMyAdmin kamu
    $query = "INSERT INTO agenda (nama_kegiatan, deskripsi, tanggal_mulai, tanggal_selesai, tempat, status) 
              VALUES ('$nama_kegiatan', '$deskripsi_kegiatan', '$tanggal_mulai', '$tanggal_selesai', '$tempat_pelaksanaan', '$status_awal')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Agenda berhasil disimpan!'); window.location='agenda.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan agenda: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Agenda Organisasi Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 sidebar p-3 d-none d-md-block">
            <div class="d-flex align-items-center mb-4 px-2">
                <span class="fs-5 fw-bold tracking-wide">SI-AGENDA</span>
            </div>
            <hr class="text-secondary">
            <ul class="nav flex-column gap-2">
                <li class="nav-item">
                    <a href="home.php" class="nav-link  py-2.5 px-3 d-flex align-items-center">
                        Dashboard Agenda
                    </a>
                </li>
                <li class="nav-item">
                    <a href="jadwal.php" class="nav-link active py-2.5 px-3">
                        Jadwal Agenda
                    </a>
                </li>
                <li class="nav-item">
                    <a href="agenda.php" class="nav-link py-2.5 px-3">
                        List Agenda
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pengurus.php" class="nav-link py-2.5 px-3">
                        Data Pengurus
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?logout=true" class="nav-link py-2.5 px-3 text-danger mt-5" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                        Keluar
                    </a>
                </li>
            </ul>
        </div>

        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h1 class="h2 fw-bold text-dark">Sistem Agenda Kegiatan</h1>
                    <p class="text-secondary small">Kelola jadwal dan kegiatan organisasi mahasiswa dengan mudah.</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <span class="badge bg-primary p-2 align-self-center"></span>
                </div>
            </div>


            <div id="form-tambah" class="card card-custom mb-5 bg-white">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold m-0 text-primary">Formulir Tambah / Edit Agenda</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="#" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nama_kegiatan" class="form-label small fw-bold">Nama Kegiatan</label>
                                <input type="text" class="form-standard form-control" id="nama_kegiatan" name="nama_kegiatan" placeholder="Contoh: LDKM 2026" required>
                            </div>
                            <div class="col-md-6">
                                <label for="tempat" class="form-label small fw-bold">Tempat Pelaksanaan</label>
                                <input type="text" class="form-control" id="tempat" name="tempat" placeholder="Contoh: Aula Fakultas Teknik" required>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="tanggal_mulai" class="form-label small fw-bold">Tanggal & Waktu Mulai</label>
                                <input type="datetime-local" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                            </div>
                            <div class="col-md-4">
                                <label for="tanggal_selesai" class="form-label small fw-bold">Tanggal & Waktu Selesai</label>
                                <input type="datetime-local" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                            </div>
                            <div class="col-md-4">
                                <label for="status" class="form-label small fw-bold">Status Awal</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="Mendatang">Mendatang</option>
                                    <option value="Berlangsung">Berlangsung</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Dibatalkan">Dibatalkan</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="deskripsi" class="form-label small fw-bold">Deskripsi Kegiatan</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Jelaskan detail singkat acaramu di sini..." required></textarea>
                            </div>

                            <div class="col-12 text-end mt-4">
                                <button type="reset" class="btn btn-light me-2 px-4">Reset</button>
                                <button type="submit" name="simpan" class="btn btn-success px-4">Simpan Agenda</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            

        </div>
    </div>
</div>

</body>
<!-- Inject fitur gacor tanpa merusak kodingan asli -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="script.js?v=<?php echo time(); ?>"></script>
</html>