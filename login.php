<?php
session_start();
ob_start();

$host = "localhost"; 
$user = "root";      
$pass = "";          
$db   = "db_agenda_mahasiswa"; 

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $eksekusi = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($eksekusi) > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['status'] = "login";
        header("location:home.php"); 
        exit;
    } else {
        echo "<script>alert('Username atau Password Salah!'); window.location='login.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Agenda Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ============================================
           LOGIN.PHP - LOGIN FORM STYLE
           ============================================ */
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #4f46e5 80%, #0ea5e9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        
        /* ===== BACKGROUND DECORATIONS ===== */
        body::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: rgba(79, 70, 229, 0.15);
            border-radius: 50%;
            top: -250px;
            right: -200px;
            animation: float 8s ease-in-out infinite;
        }
        
        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(14, 165, 233, 0.10);
            border-radius: 50%;
            bottom: -200px;
            left: -150px;
            animation: float 10s ease-in-out infinite reverse;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.05); }
        }
        
        /* ===== DECORATIVE ORBS ===== */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            pointer-events: none;
        }
        
        .orb-1 {
            width: 300px;
            height: 300px;
            background: #4f46e5;
            top: 10%;
            left: 5%;
            animation: pulse 6s ease-in-out infinite;
        }
        
        .orb-2 {
            width: 200px;
            height: 200px;
            background: #0ea5e9;
            bottom: 15%;
            right: 10%;
            animation: pulse 8s ease-in-out infinite reverse;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.2; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.2); }
        }
        
        /* ===== LOGIN CONTAINER ===== */
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideUp 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        
        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* ===== LOGO / HEADER ===== */
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header .login-logo {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #4f46e5, #0ea5e9);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 800;
            color: white;
            margin: 0 auto 1rem;
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .login-header .login-logo:hover {
            transform: scale(1.05) rotate(-5deg);
            box-shadow: 0 12px 35px rgba(79, 70, 229, 0.4);
        }
        
        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        
        .login-header h1 span {
            background: linear-gradient(135deg, #4f46e5, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .login-header p {
            color: #64748b;
            font-size: 0.9rem;
            margin: 0.25rem 0 0;
            font-weight: 400;
        }
        
        .login-header .divider-line {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #4f46e5, #0ea5e9);
            border-radius: 10px;
            margin: 1rem auto 0;
        }
        
        /* ===== FORM ===== */
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-label-custom {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .form-label-custom .label-icon {
            margin-right: 0.4rem;
        }
        
        .input-group-custom {
            position: relative;
        }
        
        .input-group-custom .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            pointer-events: none;
            z-index: 2;
        }
        
        .form-control-custom {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fafbfc;
            color: #0f172a;
        }
        
        .form-control-custom:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            background: #ffffff;
            outline: none;
        }
        
        .form-control-custom::placeholder {
            color: #94a3b8;
        }
        
        /* ===== PASSWORD TOGGLE ===== */
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 1rem;
            padding: 0.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 2;
        }
        
        .password-toggle:hover {
            color: #4f46e5;
        }
        
        /* ===== BUTTON ===== */
        .btn-login {
            width: 100%;
            padding: 0.9rem;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #4f46e5, #0ea5e9);
            color: white;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            margin-top: 0.5rem;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(79, 70, 229, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login .btn-icon {
            font-size: 1.2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-login:hover .btn-icon {
            transform: translateX(4px);
        }
        
        /* ===== FOOTER ===== */
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f1f5f9;
        }
        
        .login-footer p {
            color: #94a3b8;
            font-size: 0.8rem;
            margin: 0;
        }
        
        .login-footer p .highlight {
            color: #4f46e5;
            font-weight: 600;
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 480px) {
            .login-container {
                padding: 1.75rem;
                margin: 0 0.5rem;
            }
            
            .login-header h1 {
                font-size: 1.5rem;
            }
            
            .login-header .login-logo {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
            
            .form-control-custom {
                padding: 0.75rem 1rem 0.75rem 2.5rem;
                font-size: 0.9rem;
            }
        }
        
        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(79, 70, 229, 0.3);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(79, 70, 229, 0.5);
        }
    </style>
</head>
<body>
    
    <!-- Decorative Orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <div class="login-logo">📋</div>
            <h1>SI-<span>AGENDA</span></h1>
            <p>Sistem Agenda Kegiatan Mahasiswa</p>
            <div class="divider-line"></div>
        </div>

        <!-- Login Form -->
        <form method="POST" action="">
            <!-- Username -->
            <div class="form-group">
                <label class="form-label-custom" for="username">
                    <span class="label-icon">👤</span> Username
                </label>
                <div class="input-group-custom">
                    <span class="input-icon">👤</span>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="form-control-custom" 
                           placeholder="Masukkan username" 
                           required>
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="form-label-custom" for="password">
                    <span class="label-icon">🔒</span> Password
                </label>
                <div class="input-group-custom">
                    <span class="input-icon">🔒</span>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control-custom" 
                           placeholder="Masukkan password" 
                           required>
                    <button type="button" 
                            class="password-toggle" 
                            id="togglePassword" 
                            aria-label="Toggle password visibility">
                        👁️
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="login" class="btn-login">
                <span>Masuk</span>
                <span class="btn-icon">→</span>
            </button>
        </form>

        <!-- Footer -->
        <div class="login-footer">
            <p>
                Hubungi <span class="highlight">Admin</span> jika lupa username atau password
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password Toggle Visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            // Change icon
            this.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
        });
    </script>
</body>
</html>