<?php
// 1. Hubungkan ke database asli
$koneksi = mysqli_connect("localhost", "root", "", "db_agenda_mahasiswa");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// 2. Ambil data pengurus secara dinamis dari tabel database
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
        .card-pengurus {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            background-color: #fff;
        }
        .card-pengurus:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.15);
        }
        .foto-placeholder {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }
        .pengurus-info {
            padding: 1.5rem;
        }
        .pengurus-nama {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }
        .pengurus-jabatan {
            font-size: 0.85rem;
            color: white;
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 0.75rem;
            font-weight: 500;
        }
        .pengurus-detail {
            font-size: 0.85rem;
            line-height: 1.6;
            margin-bottom: 0.5rem;
        }
        .pengurus-detail-label {
            color: #64748b;
            font-weight: 500;
        }
        .pengurus-detail-value {
            color: #1e293b;
            word-break: break-word;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar p-3 d-none d-md-block">
            <div class="d-flex align-items-center mb-4 px-2">
                <span class="fs-5 fw-bold text-white">SI-AGENDA</span>
            </div>
            <hr class="text-secondary">
            <ul class="nav flex-column gap-2">
                <li class="nav-item">
                    <a href="home.php" class="nav-link py-2.5 px-3">Dashboard Agenda</a>
                </li>
                <li class="nav-item">
                    <a href="jadwal.php" class="nav-link py-2.5 px-3">Jadwal Agenda</a>
                </li>
                <li class="nav-item">
                    <a href="agenda.php" class="nav-link py-2.5 px-3">List Agenda</a>
                </li>
                <li class="nav-item">
                    <a href="pengurus.php" class="nav-link active py-2.5 px-3">Data Pengurus</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link py-2.5 px-3 text-danger mt-5">Keluar</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <div>
                    <h1 class="h2 fw-bold text-dark">Data Pengurus Organisasi</h1>
                    <p class="text-secondary small">Kelola dan lihat informasi pengurus organisasi mahasiswa.</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <span class="badge bg-primary p-2 align-self-center">Sesi: Admin Utama</span>
                </div>
            </div>

            <!-- Filter & Search -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="card border-0 rounded-3 p-3 bg-white" style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small">Cari Pengurus</label>
                                <input type="text" class="form-control" id="searchPengurus" placeholder="Cari berdasarkan nama atau nim...">
                            </div>
                            <div class="col-md-6 d-flex justify-content-md-end">
                                <a href="tambah_pengurus.php" class="btn btn-primary px-4">+ Tambah Pengurus</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Pengurus (Looping dari Database) -->
            <div class="row g-4" id="daftarPengurus">
                
                <?php 
                if ($total_data > 0) {
                    while ($row = mysqli_fetch_assoc($query)) { 
                ?>
                <div class="col-sm-6 col-md-6 col-lg-4 pengurus-item">
                    <div class="card card-pengurus">
                        <div class="foto-placeholder"><span>👤</span></div>
                        <div class="pengurus-info">
                            <!-- Menampilkan nama_lengkap sesuai database -->
                            <div class="pengurus-nama"><?= htmlspecialchars($row['nama_lengkap']); ?></div>
                            <div class="pengurus-jabatan"><?= htmlspecialchars($row['jabatan']); ?></div>
                            <div class="pengurus-detail">
                                <span class="pengurus-detail-label">NIM:</span>
                                <span class="pengurus-detail-value info-nim"><?= htmlspecialchars($row['nim']); ?></span>
                            </div>
                            <div class="pengurus-detail">
                                <span class="pengurus-detail-label">Email:</span>
                                <span class="pengurus-detail-value"><?= htmlspecialchars($row['email']); ?></span>
                            </div>
                            <div class="d-flex gap-2 mt-3">
                                <a href="edit_pengurus.php?nim=<?= urlencode($row['nim']); ?>" class="btn btn-sm btn-outline-primary flex-grow-1">Edit</a>
                                <a href="hapus_pengurus.php?nim=<?= urlencode($row['nim']); ?>" class="btn btn-sm btn-outline-danger">Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                    } 
                } else { 
                ?>
                <div class="col-12 text-center py-5">
                    <p class="text-secondary mb-0">Belum ada data pengurus di database.</p>
                </div>
                <?php } ?>

            </div>

            <!-- Footer Info -->
            <div class="row mt-4">
                <div class="col-12">
                    <p class="text-secondary small text-center mb-0">
                        Total Pengurus: <strong id="totalPengurus"><?= $total_data; ?></strong>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Real-time Search Logic
    document.getElementById('searchPengurus').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let items = document.querySelectorAll('.pengurus-item');
        let count = 0;

        items.forEach(function(item) {
            let nama = item.querySelector('.pengurus-nama').innerText.toLowerCase();
            let nim = item.querySelector('.info-nim').innerText.toLowerCase();
            
            if (nama.includes(filter) || nim.includes(filter)) {
                item.removeAttribute('style'); // Mengembalikan ke display aslinya agar grid tidak hancur
                count++;
            } else {
                item.style.setProperty('display', 'none', 'important');
            }
        });

        document.getElementById('totalPengurus').innerText = count;
    });
</script>
</body>
</html>