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
$db   = "db_agenda_mahasiswa"; 

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 3. Menghitung Statistik
$q_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM agenda");
$r_total = mysqli_fetch_assoc($q_total);
$total_agenda = $r_total['total'];

$q_berlangsung = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM agenda WHERE status = 'Berlangsung'");
$r_berlangsung = mysqli_fetch_assoc($q_berlangsung);
$agenda_berlangsung = $r_berlangsung['total'];

$q_mendatang = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM agenda WHERE status = 'Mendatang'");
$r_mendatang = mysqli_fetch_assoc($q_mendatang);
$agenda_mendatang = $r_mendatang['total'];

$q_pengurus = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pengurus");
$r_pengurus = mysqli_fetch_assoc($q_pengurus);
$total_pengurus = $r_pengurus['total'];

// 4. Ambil 3 Agenda Mendatang
$query_list_mendatang = mysqli_query($koneksi, "SELECT * FROM agenda WHERE status = 'Mendatang' ORDER BY id_agenda DESC LIMIT 3");

// 5. Ambil 4 aktivitas terbaru
$query_aktivitas = mysqli_query($koneksi, "SELECT * FROM agenda ORDER BY created_at DESC LIMIT 4");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Agenda Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ============================================
           HOME.PHP - DASHBOARD STYLE
           ============================================ */
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            min-height: 100vh;
        }
        
        /* ===== SIDEBAR ===== */
        .sidebar {
            background: linear-gradient(180deg, #1e1b4b 0%, #312e81 40%, #4f46e5 100%);
            min-height: 100vh;
            color: #fff;
            padding: 1.5rem 1rem !important;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            margin-bottom: 2rem;
        }
        
        .sidebar .brand .logo-icon {
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 800;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .brand .brand-text {
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            background: linear-gradient(135deg, #ffffff 0%, #c7d2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .sidebar .divider {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, rgba(255,255,255,0.1), rgba(255,255,255,0.01));
            margin: 0.5rem 0 1.5rem 0;
        }
        
        .sidebar .nav {
            gap: 0.25rem;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.6);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
        }
        
        .sidebar .nav-link .nav-icon {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
            opacity: 0.7;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }
        
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 20px rgba(79, 70, 229, 0.3);
        }
        
        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 24px;
            background: #818cf8;
            border-radius: 0 4px 4px 0;
        }
        
        .sidebar .nav-link.text-danger {
            color: rgba(239, 68, 68, 0.6) !important;
            margin-top: 1.5rem;
        }
        
        .sidebar .nav-link.text-danger:hover {
            color: #ef4444 !important;
            background: rgba(239, 68, 68, 0.1);
        }
        
        /* ===== MAIN CONTENT ===== */
        .main-content {
            padding: 2rem 2rem 2rem 2rem;
        }
        
        /* ===== WELCOME BANNER ===== */
        .welcome-banner {
            background: linear-gradient(135deg, #1e1b4b 0%, #4f46e5 50%, #0ea5e9 100%);
            border-radius: 16px;
            padding: 2.5rem;
            color: white;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
        }
        
        .welcome-banner::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: 10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 50%;
        }
        
        .welcome-banner h2 {
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }
        
        .welcome-banner p {
            opacity: 0.85;
            margin: 0;
            position: relative;
            z-index: 1;
            font-size: 1rem;
        }
        
        .welcome-banner .welcome-emoji {
            position: absolute;
            right: 2.5rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 4rem;
            opacity: 0.15;
            z-index: 0;
        }
        
        /* ===== STATISTICS CARDS ===== */
        .stat-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            background: #ffffff;
            position: relative;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4f46e5, #0ea5e9);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .stat-card:hover::before {
            opacity: 1;
        }
        
        .stat-card-body {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }
        
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
        }
        
        .stat-icon.primary {
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            color: #4f46e5;
        }
        
        .stat-icon.success {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #22c55e;
        }
        
        .stat-icon.warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #f59e0b;
        }
        
        .stat-icon.danger {
            background: linear-gradient(135deg, #fee2e2, #fca5a5);
            color: #ef4444;
        }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: #475569;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* ===== QUICK ACTIONS ===== */
        .quick-action-btn {
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            color: #0f172a;
            display: block;
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        
        .quick-action-btn:hover {
            border-color: #4f46e5;
            background: #f8fafc;
            color: #4f46e5;
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .quick-action-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .quick-action-btn:hover .quick-action-icon {
            transform: scale(1.1);
        }
        
        .quick-action-label {
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        /* ===== EVENT CARDS ===== */
        .event-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #ffffff;
            border-left: 4px solid #4f46e5;
        }
        
        .event-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transform: translateX(4px);
        }
        
        .event-date {
            background: #f1f5f9;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            color: #4f46e5;
            text-align: center;
            min-width: 70px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .event-card:hover .event-date {
            background: #4f46e5;
            color: white;
        }
        
        .badge-status {
            font-size: 0.7rem;
            padding: 0.35rem 0.8rem;
            font-weight: 600;
            border-radius: 50px;
            letter-spacing: 0.3px;
        }
        
        /* ===== TIMELINE ===== */
        .timeline-item {
            position: relative;
            padding-left: 2rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .timeline-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 6px;
            width: 12px;
            height: 12px;
            background: #4f46e5;
            border-radius: 50%;
            border: 3px solid #eef2ff;
            box-shadow: 0 0 0 3px #4f46e5;
        }
        
        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 24px;
            width: 2px;
            height: calc(100% + 0.5rem);
            background: linear-gradient(180deg, #818cf8, transparent);
        }
        
        .timeline-item .time-text {
            font-size: 0.7rem;
            color: #94a3b8;
            font-weight: 500;
        }
        
        .timeline-item .title-text {
            font-weight: 600;
            color: #0f172a;
            margin: 0.25rem 0;
        }
        
        .timeline-item .desc-text {
            font-size: 0.8rem;
            color: #475569;
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
                height: auto;
                position: relative;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .welcome-banner {
                padding: 1.5rem;
            }
            
            .welcome-banner h2 {
                font-size: 1.3rem;
            }
            
            .stat-number {
                font-size: 1.4rem;
            }
            
            .stat-icon {
                width: 44px;
                height: 44px;
                font-size: 1.2rem;
            }
        }
        
        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #e2e8f0;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #818cf8;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #4f46e5;
        }
        
        /* ===== SECTION TITLE ===== */
        .section-title {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        
        .section-title .title-icon {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- ===== SIDEBAR ===== -->
        <div class="col-md-3 col-lg-2 sidebar p-3 d-none d-md-block">
            <div class="brand">
                <div class="logo-icon">📋</div>
                <span class="brand-text">SI-AGENDA</span>
            </div>
            <hr class="divider">
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a href="home.php" class="nav-link active">
                        <span class="nav-icon">📊</span> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="jadwal.php" class="nav-link">
                        <span class="nav-icon">📅</span> Jadwal Agenda
                    </a>
                </li>
                <li class="nav-item">
                    <a href="agenda.php" class="nav-link">
                        <span class="nav-icon">📋</span> List Agenda
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pengurus.php" class="nav-link">
                        <span class="nav-icon">👥</span> Data Pengurus
                    </a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">
                        <span class="nav-icon">🚪</span> Keluar
                    </a>
                </li>
            </ul>
        </div>

        <!-- ===== MAIN CONTENT ===== -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
            
            <!-- Welcome Banner -->
            <div class="welcome-banner">
                <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?>! 👋</h2>
                <p>Kelola jadwal dan kegiatan organisasi mahasiswa dengan mudah.</p>
                <span class="welcome-emoji">🎯</span>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card">
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
                    <div class="stat-card">
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
                    <div class="stat-card">
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
                    <div class="stat-card">
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
            <h5 class="section-title"><span class="title-icon">⚡</span>Aksi Cepat</h5>
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

            <!-- Upcoming Events & Timeline -->
            <div class="row g-4">
                <div class="col-lg-8">
                    <h5 class="section-title"><span class="title-icon">📌</span>Agenda Mendatang</h5>
                    
                    <?php 
                    if(mysqli_num_rows($query_list_mendatang) > 0) {
                        while($row = mysqli_fetch_array($query_list_mendatang)) { 
                    ?>
                    <div class="card event-card mb-3">
                        <div class="card-body p-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                    <div class="event-date">
                                        <div style="font-size: 1rem; font-weight: 700;"><?= date('d', strtotime($row['tanggal_mulai'])); ?></div>
                                        <div style="font-size: 0.7rem; opacity: 0.7; text-transform: uppercase;"><?= date('M', strtotime($row['tanggal_mulai'])); ?></div>
                                    </div>
                                </div>
                                <div class="col">
                                    <h6 class="card-title fw-bold mb-1"><?= htmlspecialchars($row['nama_kegiatan']); ?></h6>
                                    <p class="text-secondary small mb-1">📍 <?= htmlspecialchars($row['tempat']); ?> | ⏰ <?= date('H:i', strtotime($row['tanggal_mulai'])); ?> - <?= date('H:i', strtotime($row['tanggal_selesai'])); ?> WIB</p>
                                    <p class="text-secondary small mb-0"><?= htmlspecialchars($row['deskripsi']); ?></p>
                                </div>
                                <div class="col-auto">
                                    <span class="badge badge-status bg-warning text-dark"><?= $row['status']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                        } 
                    } else {
                        echo "<div class='alert alert-light text-secondary small'>Belum ada agenda mendatang terdekat.</div>";
                    }
                    ?>
                </div>

                <!-- Recent Activity -->
                <div class="col-lg-4">
                    <h5 class="section-title"><span class="title-icon">🔄</span>Aktivitas Terbaru</h5>
                    
                    <div class="card border-0 rounded-3 shadow-sm">
                        <div class="card-body">
                            <?php 
                            if(mysqli_num_rows($query_aktivitas) > 0) {
                                while($act = mysqli_fetch_array($query_aktivitas)) { 
                            ?>
                            <div class="timeline-item">
                                <div class="time-text"><?= date('d M Y', strtotime($act['created_at'])); ?> | <?= date('H:i', strtotime($act['created_at'])); ?> WIB</div>
                                <div class="title-text">Agenda baru ditambahkan</div>
                                <div class="desc-text"><?= htmlspecialchars($act['nama_kegiatan']); ?></div>
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