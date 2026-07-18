<?php
session_start();

// 1. Proteksi Halaman
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("location:login.php");
    exit;
}

// 2. KONEKSI DATABASE
$host     = "localhost";
$username = "root";
$password = "";
$database = "db_agenda_mahasiswa";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// 3. AMBIL DATA LAMA UNTUK DITAMPILKAN DI FORM
if (!isset($_GET['nim']) || empty($_GET['nim'])) {
    echo "<script>alert('NIM tidak ditentukan!'); window.location='pengurus.php';</script>";
    exit;
}

$nim_url = mysqli_real_escape_string($koneksi, $_GET['nim']);

// Menyesuaikan query ke tabel 'pengurus' berdasarkan kolom 'nim'
$query_get = "SELECT * FROM pengurus WHERE nim = '$nim_url' LIMIT 1"; 
$result_get = mysqli_query($koneksi, $query_get);

if (!$result_get) {
    die("Query Error: " . mysqli_error($koneksi));
}

$data = mysqli_fetch_assoc($result_get);

// Jika data tidak ditemukan di database
if (!$data) {
    echo "<script>alert('Data pengurus tidak ditemukan!'); window.location='pengurus.php';</script>";
    exit;
}

// 4. PROSES SIMPAN PERUBAHAN DATA (SAAT FORM DI-SUBMIT)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil input formulir
    $nim_baru     = mysqli_real_escape_string($koneksi, $_POST['nim']);
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $jabatan      = mysqli_real_escape_string($koneksi, $_POST['jabatan']);
    $email        = mysqli_real_escape_string($koneksi, $_POST['email']);
    
    // Query UPDATE disesuaikan dengan struktur kolom asli database Anda
    $query_update = "UPDATE pengurus SET 
                        nim = '$nim_baru', 
                        nama_lengkap = '$nama_lengkap', 
                        jabatan = '$jabatan',
                        email = '$email'
                     WHERE nim = '$nim_url'";

    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Data pengurus berhasil diperbarui!'); window.location='pengurus.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menyimpan perubahan: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengurus - Sistem Agenda Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ============================================
           EDIT_PENGURUS.PHP - EDIT PENGURUS STYLE
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
        
        /* ===== FORM CARD ===== */
        .form-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .form-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .form-card .card-header-custom {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f59e0b, #f97316);
            border-bottom: none;
        }
        
        .form-card .card-header-custom h5 {
            font-weight: 700;
            margin: 0;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .form-card .card-header-custom h5 .header-icon {
            font-size: 1.4rem;
        }
        
        .form-card .card-body-custom {
            padding: 2rem;
        }
        
        /* ===== PROFILE PREVIEW ===== */
        .profile-preview {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            border: 2px dashed #e2e8f0;
        }
        
        .profile-preview .avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #0ea5e9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            flex-shrink: 0;
        }
        
        .profile-preview .info {
            flex: 1;
        }
        
        .profile-preview .info .name {
            font-weight: 700;
            font-size: 1.1rem;
            color: #0f172a;
        }
        
        .profile-preview .info .role {
            font-size: 0.85rem;
            color: #475569;
        }
        
        .profile-preview .info .role strong {
            color: #4f46e5;
        }
        
        /* ===== FORM STYLING ===== */
        .form-label-custom {
            font-weight: 600;
            color: #475569;
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
            display: block;
        }
        
        .form-label-custom .required {
            color: #ef4444;
            margin-left: 0.25rem;
        }
        
        .form-control-custom {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fafbfc;
            width: 100%;
            color: #0f172a;
        }
        
        .form-control-custom:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);
            background: #ffffff;
            outline: none;
        }
        
        .form-control-custom::placeholder {
            color: #94a3b8;
        }
        
        /* ===== FORM ROW ===== */
        .form-row {
            margin-bottom: 1.5rem;
        }
        
        /* ===== BUTTONS ===== */
        .btn-custom {
            padding: 0.7rem 1.8rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-custom.btn-primary-custom {
            background: linear-gradient(135deg, #f59e0b, #f97316);
            color: white;
        }
        
        .btn-custom.btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.35);
        }
        
        .btn-custom.btn-back {
            background: #f1f5f9;
            color: #475569;
            text-decoration: none;
        }
        
        .btn-custom.btn-back:hover {
            background: #e2e8f0;
            transform: translateY(-3px);
        }
        
        .form-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid #f1f5f9;
        }
        
        /* ===== INFO BANNER ===== */
        .info-banner {
            background: #fffbeb;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #f59e0b;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .info-banner .info-icon {
            font-size: 1.5rem;
        }
        
        .info-banner .info-text {
            font-size: 0.9rem;
            color: #92400e;
            margin: 0;
        }
        
        .info-banner .info-text strong {
            color: #78350f;
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
            
            .form-card .card-body-custom {
                padding: 1.25rem;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .form-actions .btn-custom {
                width: 100%;
                justify-content: center;
            }
            
            .profile-preview {
                flex-direction: column;
                text-align: center;
            }
            
            .info-banner {
                flex-direction: column;
                text-align: center;
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
            <div class="page-header">
                <h1>✏️ Edit Data Pengurus</h1>
                <p>Ubah informasi lengkap pengurus yang dipilih.</p>
            </div>

            <!-- Info Banner -->
            <div class="info-banner">
                <span class="info-icon">⚠️</span>
                <p class="info-text">
                    <strong>Perhatian:</strong> Pastikan data yang dimasukkan sudah benar. 
                    Perubahan akan langsung berlaku di sistem.
                </p>
            </div>

            <!-- Profile Preview -->
            <div class="profile-preview">
                <div class="avatar">👤</div>
                <div class="info">
                    <div class="name"><?= htmlspecialchars($data['nama_lengkap'] ?? ''); ?></div>
                    <div class="role">
                        Sedang mengedit data pengurus dengan NIM: 
                        <strong><?= htmlspecialchars($data['nim'] ?? ''); ?></strong>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="form-card">
                <div class="card-header-custom">
                    <h5>
                        <span class="header-icon">✏️</span>
                        Formulir Edit Pengurus
                    </h5>
                </div>
                <div class="card-body-custom">
                    <form method="POST" action="">
                        <div class="row">
                            <!-- NIM -->
                            <div class="col-md-6 form-row">
                                <label for="nim" class="form-label-custom">
                                    NIM <span class="required">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control-custom" 
                                       id="nim" 
                                       name="nim" 
                                       value="<?= htmlspecialchars($data['nim'] ?? ''); ?>" 
                                       required>
                            </div>
                            
                            <!-- Nama Lengkap -->
                            <div class="col-md-6 form-row">
                                <label for="nama_lengkap" class="form-label-custom">
                                    Nama Lengkap <span class="required">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control-custom" 
                                       id="nama_lengkap" 
                                       name="nama_lengkap" 
                                       value="<?= htmlspecialchars($data['nama_lengkap'] ?? ''); ?>" 
                                       required>
                            </div>
                            
                            <!-- Jabatan -->
                            <div class="col-md-6 form-row">
                                <label for="jabatan" class="form-label-custom">
                                    Jabatan <span class="required">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control-custom" 
                                       id="jabatan" 
                                       name="jabatan" 
                                       value="<?= htmlspecialchars($data['jabatan'] ?? ''); ?>" 
                                       required>
                            </div>
                            
                            <!-- Email -->
                            <div class="col-md-6 form-row">
                                <label for="email" class="form-label-custom">
                                    Email <span class="required">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control-custom" 
                                       id="email" 
                                       name="email" 
                                       value="<?= htmlspecialchars($data['email'] ?? ''); ?>" 
                                       required>
                            </div>
                            
                            <!-- Actions -->
                            <div class="col-12">
                                <div class="form-actions">
                                    <a href="pengurus.php" class="btn-custom btn-back">
                                        ↩️ Kembali
                                    </a>
                                    <button type="submit" class="btn-custom btn-primary-custom">
                                        💾 Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>