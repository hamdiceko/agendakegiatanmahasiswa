<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengurus - Sistem Agenda Mahasiswa</title>
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
        .preview-foto {
            max-width: 200px;
            max-height: 200px;
            margin-top: 1rem;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            display: none;
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
                    <h1 class="h2 fw-bold text-dark">Tambah Pengurus Baru</h1>
                    <p class="text-secondary small">Masukkan data pengurus dan upload foto profil.</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <span class="badge bg-primary p-2 align-self-center">Sesi: Admin Utama</span>
                </div>
            </div>

            <div class="card border-0 rounded-3 bg-white" style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <div class="card-body p-4">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="nim" class="form-label fw-semibold">NIM</label>
                                <input type="text" class="form-control" id="nim" name="nim" placeholder="Masukkan NIM" required>
                            </div>

                            <div class="col-md-6">
                                <label for="nama_lengkap" class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap" required>
                            </div>

                            <div class="col-md-6">
                                <label for="jabatan" class="form-label fw-semibold">Jabatan</label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Contoh: Ketua, Wakil Ketua, Sekretaris" required>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email">
                            </div>

                            <div class="col-12">
                                <label for="foto" class="form-label fw-semibold">Foto Profil</label>
                                <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewFoto(event)">
                                <small class="text-secondary d-block mt-2">Format: JPG, PNG, GIF. Ukuran maksimal: 5MB</small>
                                <img id="preview" class="preview-foto" alt="Preview Foto">
                            </div>

                            <div class="col-12 text-end mt-4">
                                <a href="pengurus.php" class="btn btn-light me-2 px-4">Batal</a>
                                <button type="submit" class="btn btn-success px-4">Simpan Pengurus</button>
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
