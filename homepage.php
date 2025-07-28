<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Desa Ngunut</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <!-- BoxIcons -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
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
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        .hero-section {
            background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('asset/img/pattern.png');
            opacity: 0.1;
        }

        .main-search-input-wrap {
            background: white;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-top: 30px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .main-search-input {
            display: flex;
            gap: 10px;
        }

        .main-search-input-item {
            flex: 1;
        }

        .main-search-input-item input {
            width: 100%;
            padding: 15px;
            border: 2px solid #eee;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .main-search-input-item input:focus {
            border-color: #0d6efd;
            outline: none;
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.2);
        }

        .main-search-button {
            background: #0d6efd;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .main-search-button:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        .features-section {
            padding: 80px 0;
        }

        .feature-card {
            text-align: center;
            padding: 30px;
            border-radius: 15px;
            background: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .feature-icon {
            font-size: 40px;
            color: #0d6efd;
            margin-bottom: 20px;
        }

        .modal-content {
            border-radius: 15px;
        }

        .modal-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .btn-close {
            color: white;
        }

        footer {
            background: #343a40;
            color: white;
            padding: 30px 0;
            margin-top: 50px;
        }
        .about-hero {
            background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
            color: white;
            padding: 60px 0 40px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .about-hero img {
            width: 120px;
            border-radius: 50%;
            margin-bottom: 20px;
            border: 5px solid #fff;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .about-card {
            background: rgba(255,255,255,0.7);
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.15);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255,255,255,0.18);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .about-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 16px 40px 0 rgba(31,38,135,0.18);
        }
        .about-icon {
            font-size: 3rem;
            color: #0d6efd;
            margin-bottom: 10px;
            filter: drop-shadow(0 2px 8px #0d6efd33);
        }
        .about-badge {
            display: inline-block;
            background: #0d6efd;
            color: #fff;
            border-radius: 12px;
            padding: 2px 12px;
            font-size: 0.95rem;
            margin-right: 8px;
            margin-bottom: 4px;
        }
        .about-link {
            color: #0d6efd;
            font-weight: 500;
            text-decoration: underline;
        }
        .about-link:hover {
            color: #0099ff;
        }
        .map-responsive { overflow: hidden; padding-bottom: 56.25%; position: relative; height: 0; }
        .map-responsive iframe { left: 0; top: 0; height: 100%; width: 100%; position: absolute; border-radius: 15px; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark animate__animated animate__fadeInDown">
        <div class="container">
            <a class="navbar-brand" href="#beranda">
                <i class='bx bxs-building-house'></i> 
                Desa Ngunut
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#beranda"><i class='bx bxs-home'></i> Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php"><i class='bx bxs-info-circle'></i> Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><i class='bx bxs-user'></i> Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <a id="beranda"></a>
    <div class="hero-section">
        <div class="container text-center">
            <h1 class="animate__animated animate__fadeInUp">Selamat Datang di Sistem Informasi Desa Ngunut</h1>
            <p class="animate__animated animate__fadeInUp animate__delay-1s">
                Cek status kependudukan Anda dengan memasukkan NIK
            </p>
            <div class="main-search-input-wrap animate__animated animate__fadeInUp animate__delay-2s">
                <div class="main-search-input">
                    <div class="main-search-input-item">
                        <input type="text" id="nikInput" placeholder="Masukkan NIK Anda...">
                    </div>
                    <button class="main-search-button" onclick="searchNIK()">
                        <i class='bx bx-search'></i> Cek Status
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 animate__animated animate__fadeInUp">
                    <div class="feature-card">
                        <i class='bx bxs-check-shield feature-icon'></i>
                        <h3>Mudah & Aman</h3>
                        <p>Proses verifikasi cepat dan terjamin keamanannya</p>
                    </div>
                </div>
                <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                    <div class="feature-card">
                        <i class='bx bxs-time-five feature-icon'></i>
                        <h3>24/7 Akses</h3>
                        <p>Layanan dapat diakses kapan saja dan dimana saja</p>
                    </div>
                </div>
                <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                    <div class="feature-card">
                        <i class='bx bxs-paper-plane feature-icon'></i>
                        <h3>Proses Cepat</h3>
                        <p>Pembuatan surat selesai dalam hitungan menit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="resultModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hasil Pencarian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <!-- Content will be inserted here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p>© 2024 Desa Ngunut, Kec. Dander. All rights reserved.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function searchNIK() {
            const nik = document.getElementById('nikInput').value;
            
            $.ajax({
                url: 'search.php',
                type: 'POST',
                data: { nik: nik },
                success: function(response) {
                    const modal = new bootstrap.Modal(document.getElementById('resultModal'));
                    const modalContent = document.getElementById('modalContent');
                    if (response.status === 'found') {
                        // Data CSV: [NO, NAMA, KELAMIN, STATUS PERKAWINAN, TEMPAT & TGL LAHIR, ...]
                        const data = response.data;
                        modalContent.innerHTML = `
                            <div class="alert alert-success">
                                <i class='bx bxs-check-circle'></i>
                                Data ditemukan:<br>
                                <b>Nama:</b> ${data[1]}<br>
                                <b>NIK:</b> ${data[15]}<br>
                                <b>Jenis Kelamin:</b> ${data[2]}<br>
                                <b>Tempat, Tgl Lahir:</b> ${data[4]}, ${data[5]}<br>
                                <b>Alamat:</b> ${data[11]} RT ${data[12]}/RW ${data[13]}
                            </div>
                            <div class="text-center">
                                <a href="login.php" class="btn btn-primary">
                                    <i class='bx bxs-user'></i> Login Sekarang
                                </a>
                            </div>`;
                    } else {
                        modalContent.innerHTML = `
                            <div class="alert alert-warning">
                                <i class='bx bxs-error'></i>
                                NIK tidak ditemukan. Silakan hubungi admin desa.
                            </div>`;
                    }
                    modal.show();
                },
                error: function() {
                    alert('Terjadi kesalahan dalam pencarian.');
                }
            });
        }
    </script>
</body>
</html>