<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Pengurus - Sistem Agenda Mahasiswa</title>
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
        .confirmation-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #dc3545;
        }
        .confirmation-icon {
            font-size: 3rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
    </style>
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
                    <a href="home.php" class="nav-link py-2.5 px-3 d-flex align-items-center">
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
                    <a href="#" class="nav-link py-2.5 px-3 text-danger mt-5">
                        Keluar
                    </a>
                </li>
            </ul>
        </div>

        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h1 class="h2 fw-bold text-dark">Hapus Data Pengurus</h1>
                    <p class="text-secondary small">Konfirmasi penghapusan data pengurus.</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <span class="badge bg-primary p-2 align-self-center">Sesi: Admin Utama</span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card confirmation-card bg-white">
                        <div class="card-body p-5 text-center">
                            <div class="confirmation-icon">
                                ⚠️
                            </div>
                            <h3 class="card-title fw-bold text-dark mb-2">Hapus Data Pengurus?</h3>
                            <p class="card-text text-secondary mb-3">Anda akan menghapus data pengurus <strong>Ahmad Rizqi</strong></p>
                            
                            <div class="alert alert-warning mb-4" role="alert">
                                <small>
                                    <strong>Perhatian:</strong> Tindakan ini tidak dapat diundur. Data pengurus dan foto profil akan dihapus secara permanen dari sistem.
                                </small>
                            </div>

                            <div class="d-flex gap-3 justify-content-center">
                                <a href="pengurus.php" class="btn btn-secondary px-4">
                                    Batal
                                </a>
                                <form method="POST" style="display: inline;">
                                    <button type="submit" class="btn btn-danger px-4">
                                        Ya, Hapus Pengurus
                                    </button>
                                </form>
                            </div>
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
