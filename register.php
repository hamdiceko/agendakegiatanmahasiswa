<?php
$koneksi = mysqli_connect("localhost", "root", "", "agendakegiatanmahasiswa");

if (isset($_POST['daftar'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'Pengurus')";
    
    // Simpan status sukses di variabel untuk ditampilkan nanti
    $sukses = mysqli_query($koneksi, $query);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Panggil SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Registrasi Pengurus</title>
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

<div class="card p-4 shadow" style="width: 100%; max-width: 400px;">
    <h3 class="text-center mb-4">Registrasi Pengurus</h3>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" name="daftar" class="btn btn-primary w-100">Daftar Sekarang</button>
    </form>
    <p class="text-center mt-3 small">Sudah punya akun? <a href="login.php">Login di sini</a></p>
</div>

<?php 
// Jika daftar berhasil, jalankan script SweetAlert
if (isset($sukses) && $sukses) {
    echo "
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Pendaftaran Sukses!',
            text: 'Akun Anda berhasil dibuat.',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = 'login.php';
        });
    </script>";
} 
?>
</body>
</html>