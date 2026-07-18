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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ============================================
           EDIT_AGENDA.PHP - EDIT AGENDA STYLE
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
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
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
        
        .form-control-custom,
        .form-select-custom {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fafbfc;
            width: 100%;
            color: #0f172a;
        }
        
        .form-control-custom:focus,
        .form-select-custom:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15);
            background: #ffffff;
            outline: none;
        }
        
        .form-control-custom::placeholder {
            color: #94a3b8;
        }
        
        textarea.form-control-custom {
            resize: vertical;
            min-height: 100px;
        }
        
        /* ===== FORM ROW ===== */
        .form-row {
            margin-bottom: 1.5rem;
        }
        
        /* ===== CURRENT STATUS BADGE ===== */
        .current-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #f8fafc;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            margin-bottom: 0.5rem;
        }
        
        .current-status .status-label {
            font-size: 0.8rem;
            color: #475569;
            font-weight: 500;
        }
        
        .current-status .status-value {
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .current-status .status-value.mendatang {
            color: #f59e0b;
        }
        .current-status .status-value.berlangsung {
            color: #22c55e;
        }
        .current-status .status-value.selesai {
            color: #475569;
        }
        .current-status .status-value.dibatalkan {
            color: #ef4444;
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
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            color: white;
        }
        
        .btn-custom.btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.35);
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
            background: #f5f3ff;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #8b5cf6;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .info-banner .info-icon {
            font-size: 1.5rem;
        }
        
        .info-banner .info-text {
            font-size: 0.9rem;
            color: #5b21b6;
            margin: 0;
        }
        
        .info-banner .info-text strong {
            color: #4c1d95;
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
            
            .info-banner {
                flex-direction: column;
                text-align: center;
            }
            
            .current-status {
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
                <h1>✏️ Edit Agenda Kegiatan</h1>
                <p>Perbarui data detail mengenai jadwal atau agenda yang dipilih.</p>
            </div>

            <!-- Info Banner -->
            <div class="info-banner">
                <span class="info-icon">💡</span>
                <p class="info-text">
                    <strong>Tips:</strong> Pastikan semua data terisi dengan benar. 
                    Perubahan akan langsung berlaku di sistem.
                </p>
            </div>

            <!-- Form Card -->
            <div class="form-card">
                <div class="card-header-custom">
                    <h5>
                        <span class="header-icon">✏️</span>
                        Formulir Edit Agenda
                    </h5>
                </div>
                <div class="card-body-custom">
                    <form method="POST">
                        <div class="row">
                            <!-- Nama Kegiatan -->
                            <div class="col-12 form-row">
                                <label for="nama_kegiatan" class="form-label-custom">
                                    Nama Kegiatan <span class="required">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control-custom" 
                                       id="nama_kegiatan" 
                                       name="nama_kegiatan" 
                                       value="<?= htmlspecialchars($data_agenda['nama_kegiatan']); ?>" 
                                       required>
                            </div>
                            
                            <!-- Tempat -->
                            <div class="col-12 form-row">
                                <label for="tempat" class="form-label-custom">
                                    Tempat Pelaksanaan <span class="required">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control-custom" 
                                       id="tempat" 
                                       name="tempat" 
                                       value="<?= htmlspecialchars($data_agenda['tempat']); ?>" 
                                       required>
                            </div>
                            
                            <!-- Tanggal Mulai & Selesai -->
                            <div class="col-md-6 form-row">
                                <label for="tanggal_mulai" class="form-label-custom">
                                    Tanggal & Waktu Mulai <span class="required">*</span>
                                </label>
                                <input type="datetime-local" 
                                       class="form-control-custom" 
                                       id="tanggal_mulai" 
                                       name="tanggal_mulai" 
                                       value="<?= date('Y-m-d\TH:i', strtotime($data_agenda['tanggal_mulai'])); ?>" 
                                       required>
                            </div>
                            
                            <div class="col-md-6 form-row">
                                <label for="tanggal_selesai" class="form-label-custom">
                                    Tanggal & Waktu Selesai <span class="required">*</span>
                                </label>
                                <input type="datetime-local" 
                                       class="form-control-custom" 
                                       id="tanggal_selesai" 
                                       name="tanggal_selesai" 
                                       value="<?= date('Y-m-d\TH:i', strtotime($data_agenda['tanggal_selesai'])); ?>" 
                                       required>
                            </div>
                            
                            <!-- Status -->
                            <div class="col-md-6 form-row">
                                <label for="status" class="form-label-custom">
                                    Status Agenda <span class="required">*</span>
                                </label>
                                
                                <!-- Current Status Info -->
                                <div class="current-status">
                                    <span class="status-label">Status Saat Ini:</span>
                                    <?php 
                                    $status_class = 'mendatang';
                                    if($data_agenda['status'] == 'Berlangsung') $status_class = 'berlangsung';
                                    elseif($data_agenda['status'] == 'Selesai') $status_class = 'selesai';
                                    elseif($data_agenda['status'] == 'Dibatalkan') $status_class = 'dibatalkan';
                                    ?>
                                    <span class="status-value <?= $status_class; ?>">
                                        <?= $data_agenda['status']; ?>
                                    </span>
                                </div>
                                
                                <select class="form-select-custom" id="status" name="status" required>
                                    <option value="Mendatang" <?= $data_agenda['status'] == 'Mendatang' ? 'selected' : ''; ?>>📌 Mendatang</option>
                                    <option value="Berlangsung" <?= $data_agenda['status'] == 'Berlangsung' ? 'selected' : ''; ?>>🟢 Berlangsung</option>
                                    <option value="Selesai" <?= $data_agenda['status'] == 'Selesai' ? 'selected' : ''; ?>>✅ Selesai</option>
                                    <option value="Dibatalkan" <?= $data_agenda['status'] == 'Dibatalkan' ? 'selected' : ''; ?>>❌ Dibatalkan</option>
                                </select>
                            </div>
                            
                            <!-- Deskripsi -->
                            <div class="col-12 form-row">
                                <label for="deskripsi" class="form-label-custom">
                                    Deskripsi Kegiatan <span class="required">*</span>
                                </label>
                                <textarea class="form-control-custom" 
                                          id="deskripsi" 
                                          name="deskripsi" 
                                          rows="4" 
                                          required><?= htmlspecialchars($data_agenda['deskripsi']); ?></textarea>
                            </div>
                            
                            <!-- Actions -->
                            <div class="col-12">
                                <div class="form-actions">
                                    <a href="agenda.php" class="btn-custom btn-back">
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