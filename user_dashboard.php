<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

if ($_SESSION['role'] == 'admin') {
    header('Location: admin_dashboard.php');
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Surat Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
            padding: 1rem 2rem;
        }

        .navbar-brand {
            color: white !important;
            font-weight: 600;
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
        }

        .hero-section {
            background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
        }

        .card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.1);
        }

        .card-body {
            padding: 1.5rem;
            text-align: center;
        }

        .card-icon {
            width: 60px;
            height: 60px;
            margin: -50px auto 20px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .card-icon i {
            font-size: 30px;
            color: #0d6efd;
        }

        .btn-custom {
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: scale(1.05);
        }

        .footer {
            background: #343a40;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="user_dashboard.php">
                <i class='bx bxs-building-house'></i> 
                Ngunut
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">
                            <i class='bx bxs-user'></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="homepage.php">
                            <i class='bx bx-log-out'></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container text-center">
            <h1 class="animate__animated animate__fadeInDown">Selamat Datang di Layanan Surat Online</h1>
            <p class="animate__animated animate__fadeInUp">Pilih jenis surat yang Anda butuhkan</p>
        </div>
    </div>

    <!-- Cards Section -->
    <div class="container">
        <div class="row g-4">
            <!-- Surat Domisili -->
            <div class="col-lg-3 col-md-6">
                <div class="card animate__animated animate__fadeInUp">
                    <img src="https://lh3.googleusercontent.com/d/1s9vAxB0YHnElFbAkYuLirN-_aV3M9nNC" class="card-img-top" alt="">
                    <div class="card-body">
                        <div class="card-icon floating">
                            <i class='bx bxs-home'></i>
                        </div>
                        <h5 class="card-title">Surat Domisili</h5>
                        <p class="card-text">Surat keterangan tempat tinggal resmi</p>
                        <a href="surat_domisili.php" class="btn btn-primary btn-custom">
                            <i class='bx bx-edit'></i> Buat Surat
                        </a>
                    </div>
                </div>
            </div>

            <!-- Surat Keterangan -->
            <div class="col-lg-3 col-md-6">
                <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                    <img src="https://lh3.googleusercontent.com/d/1sB91vqNO8DJ2NTsYFsWBbjsSKRqeHLkZ" class="card-img-top" alt="">
                    <div class="card-body">
                        <div class="card-icon floating">
                            <i class='bx bxs-file'></i>
                        </div>
                        <h5 class="card-title">Surat Keterangan</h5>
                        <p class="card-text">Surat keterangan Tidak Mampu</p>
                        <a href="surat_keterangan.php" class="btn btn-primary btn-custom">
                            <i class='bx bx-edit'></i> Buat Surat
                        </a>
                    </div>
                </div>
            </div>

            <!-- Surat Kuasa -->
            <div class="col-lg-3 col-md-6">
                <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                    <img src="https://lh3.googleusercontent.com/d/1s9ePpU6A0Dq1RdDCgDG-ivHgiKk5Ei9j" class="card-img-top" alt="">
                    <div class="card-body">
                        <div class="card-icon floating">
                            <i class='bx bxs-user-check'></i>
                        </div>
                        <h5 class="card-title">Surat Kuasa</h5>
                        <p class="card-text">Surat pemberian kuasa resmi</p>
                        <a href="surat_kuasa.php" class="btn btn-primary btn-custom">
                            <i class='bx bx-edit'></i> Buat Surat
                        </a>
                    </div>
                </div>
            </div>

            <!-- Surat Pengantar -->
            <div class="col-lg-3 col-md-6">
                <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.6s">
                    <img src="https://lh3.googleusercontent.com/d/1mImGktgpUZk0pWVTkdtTc48NkOdsX5L4" class="card-img-top" alt="">
                    <div class="card-body">
                        <div class="card-icon floating">
                            <i class='bx bxs-paper-plane'></i>
                        </div>
                        <h5 class="card-title">Surat Pengantar</h5>
                        <p class="card-text">Surat pengantar resmi</p>
                        <a href="surat_pengantar.php" class="btn btn-primary btn-custom">
                            <i class='bx bx-edit'></i> Buat Surat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <p>© 2025 Desa Ngunut, Kec.Dander. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
