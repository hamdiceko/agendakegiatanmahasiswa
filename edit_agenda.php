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

// 3. Ambil data lama agenda berdasarkan ID di URL
$id_agenda = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';

if (empty($id_agenda)) {
    header("location:agenda.php");
    exit;
}

$query_ambil = mysqli_query($koneksi, "SELECT * FROM agenda WHERE id_agenda = '$id_agenda'");
$data_agenda = mysqli_fetch_assoc($query_ambil);

if (!$data_agenda) {
    echo "<script>alert('Data agenda tidak ditemukan!'); window.location='agenda.php';</script>";
    exit;
}

// 4. Proses Update ketika tombol Simpan ditekan (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kegiatan    = mysqli_real_escape_string($koneksi, $_POST['nama_kegiatan']);
    $deskripsi        = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $tanggal_mulai    = mysqli_real_escape_string($koneksi, $_POST['tanggal_mulai']);
    $tanggal_selesai  = mysqli_real_escape_string($koneksi, $_POST['tanggal_selesai']);
    $tempat           = mysqli_real_escape_string($koneksi, $_POST['tempat']);
    $status           = mysqli_real_escape_string($koneksi, $_POST['status']);

    $query_update = mysqli_query($koneksi, "UPDATE agenda SET 
                    nama_kegiatan = '$nama_kegiatan', 
                    deskripsi = '$deskripsi', 
                    tanggal_mulai = '$tanggal_mulai', 
                    tanggal_selesai = '$tanggal_selesai', 
                    tempat = '$tempat', 
                    status = '$status' 
                    WHERE id_agenda = '$id_agenda'");

    if ($query_update) {
        echo "<script>
                alert('Agenda Berhasil Diperbarui!');
                window.location = 'agenda.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui data: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Agenda - Sistem Agenda Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            background: linear-gradient(180deg, #1e3a8a 0%, #0f172a 100%);
            min-height: 100vh;
            color: #fff;
        }
        .sidebar .nav-link {
            color: #cbd5e1;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }
    </style>
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
                <li class="nav-item"><a href="?logout=true" class="nav-link py-2.5 px-3 text-danger mt-5">Keluar</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h1 class="h2 fw-bold text-dark">Edit Agenda Kegiatan</h1>
                    <p class="text-secondary small">Perbarui data detail mengenai jadwal atau agenda yang dipilih.</p>
                </div>
            </div>

            <div class="card border-0 rounded-3 bg-white shadow-sm">
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Nama Kegiatan</label>
                                <input type="text" class="form-control" name="nama_kegiatan" value="<?= htmlspecialchars($data_agenda['nama_kegiatan']); ?>" required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" rows="3" required><?= htmlspecialchars($data_agenda['deskripsi']); ?></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal & Waktu Mulai</label>
                                <input type="datetime-local" class="form-control" name="tanggal_mulai" value="<?= date('Y-m-d\TH:i', strtotime($data_agenda['tanggal_mulai'])); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal & Waktu Selesai</label>
                                <input type="datetime-local" class="form-control" name="tanggal_selesai" value="<?= date('Y-m-d\TH:i', strtotime($data_agenda['tanggal_selesai'])); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tempat Pelaksanaan</label>
                                <input type="text" class="form-control" name="tempat" value="<?= htmlspecialchars($data_agenda['tempat']); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status Agenda</label>
                                <select class="form-select" name="status" required>
                                    <option value="Mendatang" <?= $data_agenda['status'] == 'Mendatang' ? 'selected' : ''; ?>>Mendatang</option>
                                    <option value="Berlangsung" <?= $data_agenda['status'] == 'Berlangsung' ? 'selected' : ''; ?>>Berlangsung</option>
                                    <option value="Selesai" <?= $data_agenda['status'] == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                                </select>
                            </div>

                            <div class="col-12 text-end mt-4">
                                <a href="agenda.php" class="btn btn-light me-2 px-4">Batal</a>
                                <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>