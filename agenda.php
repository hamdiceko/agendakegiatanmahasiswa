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
    <title>List Agenda - Sistem Agenda Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ============================================
           AGENDA.PHP - LIST AGENDA STYLE
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
        
        /* ===== PAGE HEADER ===== */
        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .page-header h1 {
            font-weight: 700;
            font-size: 1.8rem;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }
        
        .page-header p {
            color: #475569;
            margin: 0;
            font-size: 0.95rem;
        }
        
        .page-header .header-badge {
            background: linear-gradient(135deg, #4f46e5, #0ea5e9);
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        /* ===== TABLE CARD ===== */
        .table-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .table-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .table-card .card-header-custom {
            padding: 1.25rem 1.5rem;
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-card .card-header-custom h5 {
            font-weight: 700;
            margin: 0;
            color: #0f172a;
        }
        
        .table-card .card-header-custom .total-badge {
            background: #e2e8f0;
            color: #475569;
            padding: 0.35rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        /* ===== TABLE STYLING ===== */
        .table-agenda {
            margin-bottom: 0;
            font-size: 0.9rem;
        }
        
        .table-agenda thead th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            padding: 1rem 1.25rem;
        }
        
        .table-agenda tbody td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .table-agenda tbody tr {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .table-agenda tbody tr:hover {
            background: #f8fafc;
        }
        
        .table-agenda tbody tr:last-child td {
            border-bottom: none;
        }
        
        .table-agenda .agenda-name {
            font-weight: 600;
            color: #0f172a;
        }
        
        .table-agenda .agenda-desc {
            color: #64748b;
            font-size: 0.8rem;
            display: block;
            margin-top: 0.2rem;
        }
        
        .table-agenda .time-info {
            font-size: 0.8rem;
        }
        
        .table-agenda .time-info .date {
            font-weight: 600;
            color: #0f172a;
        }
        
        .table-agenda .time-info .time {
            color: #64748b;
        }
        
        /* ===== STATUS BADGE ===== */
        .badge-status-custom {
            font-size: 0.7rem;
            padding: 0.35rem 0.9rem;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.3px;
            display: inline-block;
        }
        
        .badge-status-custom.mendatang {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-status-custom.berlangsung {
            background: #dcfce7;
            color: #166534;
        }
        
        .badge-status-custom.selesai {
            background: #e2e8f0;
            color: #475569;
        }
        
        .badge-status-custom.dibatalkan {
            background: #fee2e2;
            color: #991b1b;
        }
        
        /* ===== ACTION BUTTONS ===== */
        .btn-action-group {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }
        
        .btn-action {
            padding: 0.35rem 0.9rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        
        .btn-action.edit {
            background: #eef2ff;
            color: #4f46e5;
            border: 1px solid #e0e7ff;
        }
        
        .btn-action.edit:hover {
            background: #4f46e5;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        
        .btn-action.delete {
            background: #fee2e2;
            color: #ef4444;
            border: 1px solid #fecaca;
        }
        
        .btn-action.delete:hover {
            background: #ef4444;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        
        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        
        .empty-state .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .empty-state h6 {
            font-weight: 600;
            color: #0f172a;
        }
        
        .empty-state p {
            color: #94a3b8;
            font-size: 0.9rem;
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
            
            .page-header h1 {
                font-size: 1.3rem;
            }
            
            .table-agenda {
                font-size: 0.8rem;
            }
            
            .table-agenda thead th,
            .table-agenda tbody td {
                padding: 0.75rem 0.75rem;
            }
            
            .btn-action {
                padding: 0.25rem 0.6rem;
                font-size: 0.7rem;
            }
            
            .table-card .card-header-custom {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
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
                    <a href="home.php" class="nav-link">
                        <span class="nav-icon">📊</span> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="jadwal.php" class="nav-link">
                        <span class="nav-icon">📅</span> Jadwal Agenda
                    </a>
                </li>
                <li class="nav-item">
                    <a href="agenda.php" class="nav-link active">
                        <span class="nav-icon">📋</span> List Agenda
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pengurus.php" class="nav-link">
                        <span class="nav-icon">👥</span> Data Pengurus
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?logout=true" class="nav-link text-danger" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                        <span class="nav-icon">🚪</span> Keluar
                    </a>
                </li>
            </ul>
        </div>

        <!-- ===== MAIN CONTENT ===== -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
            
            <!-- Page Header -->
            <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1>📋 Daftar Agenda</h1>
                    <p>Kelola semua agenda dan kegiatan organisasi mahasiswa.</p>
                </div>
                <div>
                    <span class="header-badge">📌 <?= $total_agenda; ?> Agenda</span>
                </div>
            </div>

            <!-- Table Card -->
            <div class="table-card">
                <div class="card-header-custom">
                    <h5>📋 Semua Agenda</h5>
                    <span class="total-badge">Total: <?= $total_agenda; ?> agenda</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-agenda">
                        <thead>
                            <tr>
                                <th>Nama Kegiatan</th>
                                <th>Waktu Pelaksanaan</th>
                                <th>Tempat</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($query_tampil) > 0) { 
                                while($data = mysqli_fetch_array($query_tampil)) { 
                                    // Tentukan class status
                                    $status_class = 'mendatang';
                                    if($data['status'] == 'Berlangsung') $status_class = 'berlangsung';
                                    elseif($data['status'] == 'Selesai') $status_class = 'selesai';
                                    elseif($data['status'] == 'Dibatalkan') $status_class = 'dibatalkan';
                            ?>
                            <tr>
                                <td>
                                    <div class="agenda-name"><?= htmlspecialchars($data['nama_kegiatan']); ?></div>
                                    <span class="agenda-desc"><?= htmlspecialchars($data['deskripsi']); ?></span>
                                </td>
                                <td>
                                    <div class="time-info">
                                        <div class="date"><?= date('d M Y', strtotime($data['tanggal_mulai'])); ?></div>
                                        <div class="time"><?= date('H:i', strtotime($data['tanggal_mulai'])); ?> - <?= date('H:i', strtotime($data['tanggal_selesai'])); ?> WIB</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-medium"><?= htmlspecialchars($data['tempat']); ?></span>
                                </td>
                                <td>
                                    <span class="badge-status-custom <?= $status_class; ?>">
                                        <?= $data['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-action-group">
                                        <a href="edit_agenda.php?id=<?= $data['id_agenda']; ?>" class="btn-action edit">
                                            ✏️ Edit
                                        </a>
                                        <a href="hapus_agenda.php?id=<?= $data['id_agenda']; ?>" class="btn-action delete" onclick="return confirm('Apakah Anda yakin ingin menghapus agenda ini?')">
                                            🗑️ Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php } 
                            } else { ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-icon">📭</div>
                                        <h6>Belum Ada Agenda</h6>
                                        <p>Belum ada agenda yang terdaftar di sistem. Silakan tambahkan agenda baru melalui halaman Jadwal.</p>
                                        <a href="jadwal.php" class="btn btn-primary mt-2">+ Tambah Agenda</a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>