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
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// 3. Ambil data pengurus
$query = mysqli_query($koneksi, "SELECT * FROM pengurus");
$total_data = mysqli_num_rows($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengurus - Sistem Agenda Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ============================================
           PENGURUS.PHP - DATA PENGURUS STYLE
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
        
        /* ===== SEARCH & FILTER ===== */
        .search-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            background: #ffffff;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2rem;
        }
        
        .search-card .search-label {
            font-weight: 600;
            color: #475569;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.4rem;
            display: block;
        }
        
        .search-card .search-input {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.7rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fafbfc;
            width: 100%;
            color: #0f172a;
        }
        
        .search-card .search-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            background: #ffffff;
            outline: none;
        }
        
        .search-card .search-input::placeholder {
            color: #94a3b8;
        }
        
        .btn-add {
            padding: 0.7rem 1.8rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            background: linear-gradient(135deg, #4f46e5, #0ea5e9);
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-add:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.35);
            color: white;
        }
        
        /* ===== PENGURUS CARDS ===== */
        .pengurus-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            background: #ffffff;
            height: 100%;
        }
        
        .pengurus-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .pengurus-card .card-avatar {
            width: 100%;
            height: 180px;
            background: linear-gradient(135deg, #1e1b4b 0%, #4f46e5 50%, #0ea5e9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 4rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .pengurus-card .card-avatar::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60%;
            background: linear-gradient(180deg, transparent, rgba(0,0,0,0.15));
        }
        
        .pengurus-card:hover .card-avatar {
            transform: scale(1.02);
        }
        
        .pengurus-card .card-avatar .avatar-emoji {
            position: relative;
            z-index: 1;
        }
        
        .pengurus-card .card-body-custom {
            padding: 1.5rem;
        }
        
        .pengurus-card .pengurus-nama {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.2rem;
        }
        
        .pengurus-card .pengurus-jabatan {
            font-size: 0.7rem;
            color: white;
            background: linear-gradient(135deg, #4f46e5, #0ea5e9);
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .pengurus-card .pengurus-detail {
            font-size: 0.85rem;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        
        .pengurus-card .pengurus-detail .detail-label {
            color: #94a3b8;
            font-weight: 500;
            min-width: 45px;
        }
        
        .pengurus-card .pengurus-detail .detail-value {
            color: #0f172a;
            font-weight: 500;
            word-break: break-word;
        }
        
        .pengurus-card .pengurus-detail .detail-value.nim {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #4f46e5;
        }
        
        /* ===== ACTION BUTTONS ===== */
        .pengurus-card .card-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid #f1f5f9;
        }
        
        .pengurus-card .card-actions .btn-action {
            padding: 0.4rem 1rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            flex: 1;
            justify-content: center;
        }
        
        .pengurus-card .card-actions .btn-action.edit {
            background: #eef2ff;
            color: #4f46e5;
            border: 1px solid #e0e7ff;
        }
        
        .pengurus-card .card-actions .btn-action.edit:hover {
            background: #4f46e5;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        
        .pengurus-card .card-actions .btn-action.delete {
            background: #fee2e2;
            color: #ef4444;
            border: 1px solid #fecaca;
        }
        
        .pengurus-card .card-actions .btn-action.delete:hover {
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
        
        /* ===== FOOTER ===== */
        .footer-info {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e2e8f0;
            text-align: center;
        }
        
        .footer-info p {
            color: #94a3b8;
            font-size: 0.85rem;
            margin: 0;
        }
        
        .footer-info p strong {
            color: #0f172a;
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
            
            .page-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 0.5rem;
            }
            
            .search-card .row {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .search-card .d-flex {
                flex-direction: column;
            }
            
            .btn-add {
                width: 100%;
                justify-content: center;
            }
            
            .pengurus-card .card-avatar {
                height: 140px;
                font-size: 3rem;
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
        
        /* ===== ANIMATION ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .pengurus-card {
            animation: fadeInUp 0.4s ease-out forwards;
        }
        
        .pengurus-card:nth-child(1) { animation-delay: 0.05s; }
        .pengurus-card:nth-child(2) { animation-delay: 0.1s; }
        .pengurus-card:nth-child(3) { animation-delay: 0.15s; }
        .pengurus-card:nth-child(4) { animation-delay: 0.2s; }
        .pengurus-card:nth-child(5) { animation-delay: 0.25s; }
        .pengurus-card:nth-child(6) { animation-delay: 0.3s; }
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
                    <a href="agenda.php" class="nav-link">
                        <span class="nav-icon">📋</span> List Agenda
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pengurus.php" class="nav-link active">
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
            
            <!-- Page Header -->
            <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1>👥 Data Pengurus</h1>
                    <p>Kelola dan lihat informasi pengurus organisasi mahasiswa.</p>
                </div>
                <div>
                    <span class="header-badge">👤 <?= $total_data; ?> Pengurus</span>
                </div>
            </div>

            <!-- Search & Add -->
            <div class="search-card">
                <div class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label class="search-label">🔍 Cari Pengurus</label>
                        <input type="text" 
                               class="search-input" 
                               id="searchPengurus" 
                               placeholder="Cari berdasarkan nama atau NIM...">
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-md-end">
                            <a href="tambah_pengurus.php" class="btn-add">
                                ➕ Tambah Pengurus
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Pengurus -->
            <div class="row g-4" id="daftarPengurus">
                
                <?php 
                if ($total_data > 0) {
                    while ($row = mysqli_fetch_assoc($query)) { 
                ?>
                <div class="col-sm-6 col-md-6 col-lg-4 pengurus-item">
                    <div class="pengurus-card">
                        <div class="card-avatar">
                            <span class="avatar-emoji">👤</span>
                        </div>
                        <div class="card-body-custom">
                            <div class="pengurus-nama"><?= htmlspecialchars($row['nama_lengkap']); ?></div>
                            <div class="pengurus-jabatan"><?= htmlspecialchars($row['jabatan']); ?></div>
                            
                            <div class="pengurus-detail">
                                <span class="detail-label">NIM</span>
                                <span class="detail-value nim"><?= htmlspecialchars($row['nim']); ?></span>
                            </div>
                            <div class="pengurus-detail">
                                <span class="detail-label">Email</span>
                                <span class="detail-value"><?= htmlspecialchars($row['email']); ?></span>
                            </div>
                            
                            <div class="card-actions">
                                <a href="edit_pengurus.php?nim=<?= urlencode($row['nim']); ?>" class="btn-action edit">
                                    ✏️ Edit
                                </a>
                                <a href="hapus_pengurus.php?nim=<?= urlencode($row['nim']); ?>" class="btn-action delete">
                                    🗑️ Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                    } 
                } else { 
                ?>
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <h6>Belum Ada Data Pengurus</h6>
                        <p>Belum ada pengurus yang terdaftar di sistem. Silakan tambahkan pengurus baru.</p>
                        <a href="tambah_pengurus.php" class="btn-add mt-3" style="display:inline-flex;">
                            ➕ Tambah Pengurus
                        </a>
                    </div>
                </div>
                <?php } ?>

            </div>

            <!-- Footer -->
            <div class="footer-info">
                <p>
                    Total Pengurus: <strong id="totalPengurus"><?= $total_data; ?></strong>
                </p>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Real-time Search Logic
    document.getElementById('searchPengurus').addEventListener('input', function() {
        let filter = this.value.toLowerCase().trim();
        let items = document.querySelectorAll('.pengurus-item');
        let count = 0;

        items.forEach(function(item) {
            let nama = item.querySelector('.pengurus-nama').innerText.toLowerCase();
            let nim = item.querySelector('.detail-value.nim').innerText.toLowerCase();
            
            if (nama.includes(filter) || nim.includes(filter)) {
                item.style.display = '';
                count++;
            } else {
                item.style.display = 'none';
            }
        });

        document.getElementById('totalPengurus').innerText = count;
    });
</script>
</body>
</html>