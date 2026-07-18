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
           INDEX.PHP - LOGIN/LANDING PAGE STYLE
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
            max-width: 440px;
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
        
        /* ===== FEATURES LIST ===== */
        .features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin: 1.5rem 0 2rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 12px;
        }
        
        .features .feature-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: #475569;
            font-weight: 500;
        }
        
        .features .feature-item .feat-icon {
            font-size: 1rem;
        }
        
        /* ===== BUTTON ===== */
        .btn-login-landing {
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
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }
        
        .btn-login-landing:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(79, 70, 229, 0.4);
        }
        
        .btn-login-landing:active {
            transform: translateY(0);
        }
        
        .btn-login-landing .btn-icon {
            font-size: 1.2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-login-landing:hover .btn-icon {
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
            
            .features {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            
            .login-header .login-logo {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
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

        <!-- Features -->
        <div class="features">
            <div class="feature-item">
                <span class="feat-icon">📅</span> Kelola Agenda
            </div>
            <div class="feature-item">
                <span class="feat-icon">👥</span> Data Pengurus
            </div>
            <div class="feature-item">
                <span class="feat-icon">📍</span> Jadwal Kegiatan
            </div>
            <div class="feature-item">
                <span class="feat-icon">📊</span> Dashboard
            </div>
        </div>

        <!-- Login Button -->
        <a href="login.php" class="btn-login-landing">
            <span>Masuk ke Sistem</span>
            <span class="btn-icon">→</span>
        </a>

        <!-- Footer -->
        <div class="login-footer">
            <p>
                Hubungi <span class="highlight">Admin</span> jika lupa username atau password
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>