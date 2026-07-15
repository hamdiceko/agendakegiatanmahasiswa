<?php
session_start();

// 1. Proteksi Halaman & Logout
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("location:login.php");
    exit;
}

if(isset($_GET['logout'])) {
    session_destroy();
    header("location:login.php");
    exit;
}

// 2. Koneksi ke Database
$host = "localhost"; 
$user = "root";      
$pass = "";          
$db   = "agendakegiatanmahasiswa"; 

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 3. Menghitung Statistik secara Otomatis
// Hitung Total Agenda
$q_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM agenda");
$r_total = mysqli_fetch_assoc($q_total);
$total_agenda = $r_total['total'];

// Hitung Agenda Sedang Berlangsung
$q_berlangsung = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM agenda WHERE status = 'Berlangsung'");
$r_berlangsung = mysqli_fetch_assoc($q_berlangsung);
$agenda_berlangsung = $r_berlangsung['total'];

// Hitung Agenda Akan Datang / Mendatang
$q_mendatang = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM agenda WHERE status = 'Mendatang'");
$r_mendatang = mysqli_fetch_assoc($q_mendatang);
$agenda_mendatang = $r_mendatang['total'];

// Hitung Total Pengurus
$q_pengurus = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pengurus");
$r_pengurus = mysqli_fetch_assoc($q_pengurus);
$total_pengurus = $r_pengurus['total'];

// 4. Ambil 3 Agenda Mendatang untuk list di bawah
$query_list_mendatang = mysqli_query($koneksi, "SELECT * FROM agenda WHERE status = 'Mendatang' ORDER BY id_agenda DESC LIMIT 3");

// 5. Ambil 4 aktivitas/agenda terbaru yang baru saja dibuat
$query_aktivitas = mysqli_query($koneksi, "SELECT * FROM agenda ORDER BY created_at DESC LIMIT 4");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Agenda Mahasiswa</title>
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
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.15);
        }
        .stat-card-body {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
        .stat-icon.primary {
            background-color: rgba(30, 58, 138, 0.1);
            color: #1e3a8a;
        }
        .stat-icon.success {
            background-color: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }
        .stat-icon.warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }
        .stat-icon.danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }
        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
        }
        .stat-label {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
        }
        .welcome-banner {
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
            border-radius: 12px;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
        }
        .welcome-banner h2 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .welcome-banner p {
            margin: 0;
            opacity: 0.9;
        }
        .event-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-left: 4px solid #1e3a8a;
        }
        .event-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.15);
        }
        .event-date {
            background-color: #f1f5f9;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            color: #1e3a8a;
            text-align: center;
            min-width: 80px;
        }
        .badge-status {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            font-weight: 500;
        }
        .quick-action-btn {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #1e293b;
            display: block; /* Agar memanjang penuh dan kliknya pas */
        }
        .quick-action-btn:hover {
            border-color: #1e3a8a;
            background-color: #f1f5f9;
            color: #1e3a8a;
        }
        .quick-action-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        .quick-action-label {
            font-size: 0.9rem;
            font-weight: 600;
        }
        .timeline-item {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 1.5rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 6px;
            width: 12px;
            height: 12px;
            background-color: #1e3a8a;
            border-radius: 50%;
            border: 3px solid #f1f5f9;
        }
        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 24px;
            width: 2px;
            height: 40px;
            background-color: #e2e8f0;
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
                <li class="nav-item">
                    <a href="home.php" class="nav-link active py-2.5 px-3 d-flex align-items-center">
                        Dashboard Agenda
                    </a>
                </li>
                <li class="nav-item">
                    <a href="jadwal.php" class="nav-link py-2.5 px-3">
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
                    <!-- 2. LOGOUT: Mengarahkan href ke file logout.php -->
                    <a href="logout.php" class="nav-link py-2.5 px-3 text-danger mt-5">
                        Keluar
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <!-- Welcome Banner (Menampilkan nama user yang sedang aktif login) -->
            <div class="welcome-banner mb-4">
                <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?>! 👋</h2>
                <p>Kelola jadwal dan kegiatan organisasi mahasiswa dengan mudah.</p>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon primary">📅</div>
                            <div>
                                <div class="stat-number"><?= $total_agenda; ?></div>
                                <div class="stat-label">Total Agenda</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon success">📍</div>
                            <div>
                                <div class="stat-number"><?= $agenda_berlangsung; ?></div>
                                <div class="stat-label">Sedang Berlangsung</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon warning">⏰</div>
                            <div>
                                <div class="stat-number"><?= $agenda_mendatang; ?></div>
                                <div class="stat-label">Akan Datang</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card">
                        <div class="stat-card-body">
                            <div class="stat-icon danger">👥</div>
                            <div>
                                <div class="stat-number"><?= $total_pengurus; ?></div>
                                <div class="stat-label">Total Pengurus</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <h5 class="fw-bold mb-3">Aksi Cepat</h5>
            <div class="row g-3 mb-5">
                <div class="col-md-6 col-lg-3">
                    <a href="tambah_pengurus.php" class="quick-action-btn">
                        <span class="quick-action-icon">➕</span>
                        <span class="quick-action-label">Tambah Pengurus</span>
                    </a>
                </div>
                <div class="col-md-6 col-lg-3">
                    <a href="agenda.php" class="quick-action-btn">
                        <span class="quick-action-icon">📋</span>
                        <span class="quick-action-label">Lihat Semua Agenda</span>
                    </a>
                </div>
                <div class="col-md-6 col-lg-3">
                    <a href="pengurus.php" class="quick-action-btn">
                        <span class="quick-action-icon">👥</span>
                        <span class="quick-action-label">Kelola Pengurus</span>
                    </a>
                </div>
                <div class="col-md-6 col-lg-3">
                    <a href="jadwal.php" class="quick-action-btn">
                        <span class="quick-action-icon">📅</span>
                        <span class="quick-action-label">Lihat Jadwal</span>
                    </a>
                </div>
            </div>

            <!-- Upcoming Events Section -->
            <div class="row g-4">
                <div class="col-lg-8">
                    <?php 
        // Cek apakah ada data agenda mendatang
        if(mysqli_num_rows($query_list_mendatang) > 0) {
            // Lakukan perulangan untuk setiap data
            while($row = mysqli_fetch_array($query_list_mendatang)) { 
        ?>
        <!-- Event Card (Akan diulang otomatis oleh PHP) -->
        <div class="card event-card mb-3">
            <div class="card-body p-3">
                <div class="row g-3">
                    <div class="col-auto">
                        <div class="event-date">
                            <!-- Tanggal (Contoh: 05) -->
                            <div style="font-size: 1rem;"><?= date('d', strtotime($row['tanggal_mulai'])); ?></div>
                            <!-- Bulan (Contoh: Jul) -->
                            <div style="font-size: 0.75rem; opacity: 0.7;"><?= date('M', strtotime($row['tanggal_mulai'])); ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <!-- Judul Kegiatan -->
                        <h6 class="card-title fw-bold mb-1"><?= $row['nama_kegiatan']; ?></h6>
                        <!-- Tempat & Waktu -->
                        <p class="text-secondary small mb-2">📍 <?= $row['tempat']; ?> | ⏰ <?= date('H:i', strtotime($row['tanggal_mulai'])); ?> - <?= date('H:i', strtotime($row['tanggal_selesai'])); ?> WIB</p>
                        <!-- Deskripsi -->
                        <p class="text-secondary small mb-0"><?= $row['deskripsi']; ?></p>
                    </div>
                    <div class="col-auto">
                        <span class="badge badge-status bg-warning text-dark"><?= $row['status']; ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            } // Penutup while
        } else {
            // Jika tidak ada data agenda mendatang, tampilkan pesan ini
            echo "<div class='alert alert-light text-secondary small'>Belum ada agenda mendatang terdekat.</div>";
        }
        ?>
                </div>

                <!-- Sidebar - Recent Activity -->
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-3">Aktivitas Terbaru</h5>
                    
                    <div class="card border-0 rounded-3" style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                        <div class="card-body">
                <?php 
                if(mysqli_num_rows($query_aktivitas) > 0) {
                    while($act = mysqli_fetch_array($query_aktivitas)) { 
                        // Mengatur format tanggal dan waktu created_at
                        $waktu_dibuat = date('H:i', strtotime($act['created_at']));
                        $tanggal_dibuat = date('d M Y', strtotime($act['created_at']));
                ?>
                    <div class="timeline-item mb-3">
                        <small class="text-secondary"><?= $tanggal_dibuat; ?> | <?= $waktu_dibuat; ?> WIB</small>
                        <p class="text-dark small fw-bold mb-0">Agenda baru ditambahkan</p>
                        <small class="text-secondary"><?= $act['nama_kegiatan']; ?></small>
                    </div>
                <?php 
                    } 
                } else {
                    echo "<small class='text-secondary'>Belum ada aktivitas terbaru.</small>";
                }
                ?>
            </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>