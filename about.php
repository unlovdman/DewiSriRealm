<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://www.google.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; font-src 'self' https://cdn.jsdelivr.net; img-src 'self' data: https:; frame-src 'self' https://www.google.com https://maps.google.com;">
    <title>Tentang Desa Ngunut</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #e0e7ef 0%, #f8f9fa 100%); font-family: 'Poppins', 'Segoe UI', Arial, sans-serif; }
        .hero-about {
            background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
            color: white;
            padding: 70px 0 50px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.18);
        }
        .hero-about img {
            width: 130px;
            border-radius: 50%;
            margin-bottom: 22px;
            border: 6px solid #fff;
            box-shadow: 0 6px 24px rgba(0,0,0,0.13);
            background: rgba(255,255,255,0.2);
        }
        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 18px;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px #0099ff33;
        }
        .section-subtitle {
            color: #0d6efd;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        .about-card {
            background: rgba(255,255,255,0.7);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.10);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.18);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }
        .about-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 16px 40px 0 rgba(31,38,135,0.18);
            z-index: 2;
        }
        .about-icon {
            font-size: 3.2rem;
            color: #0d6efd;
            margin-bottom: 12px;
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
            font-weight: 500;
            box-shadow: 0 2px 8px #0d6efd22;
        }
        .about-link {
            color: #0d6efd;
            font-weight: 500;
            text-decoration: underline;
        }
        .about-link:hover {
            color: #0099ff;
        }
        .map-responsive { 
            overflow: hidden; 
            padding-bottom: 56.25%; 
            position: relative; 
            height: 0; 
            border-radius: 15px;
        }
        .map-responsive iframe { 
            left: 0; 
            top: 0; 
            height: 100%; 
            width: 100%; 
            position: absolute; 
            border-radius: 15px; 
        }
        .map-fallback {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 40px 20px;
            text-align: center;
            border: 2px dashed #0d6efd;
        }
        .btn-back {
            margin-top: 40px;
            font-weight: 600;
            border-radius: 30px;
            padding: 12px 32px;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px #0d6efd22;
            background: linear-gradient(90deg, #0d6efd 60%, #0099ff 100%);
            border: none;
            transition: background 0.3s, transform 0.2s;
        }
        .btn-back:hover {
            background: linear-gradient(90deg, #0099ff 60%, #0d6efd 100%);
            transform: scale(1.04);
        }
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: #0d6efd;
            border-radius: 50%;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="hero-about animate__animated animate__fadeInDown animate__faster">
        <a href="homepage.php"><img src="asset/img/desa.jpg" alt="Desa Ngunut"></a>
        <h1 class="section-title animate__animated animate__fadeInUp animate__delay-1s">Tentang Desa Ngunut</h1>
        <p class="lead animate__animated animate__fadeInUp animate__delay-2s">Desa ramah, maju, dan berbudaya di Kecamatan Dander, Bojonegoro</p>
    </div>
    <div class="container my-5">
        <div class="row mb-4">
            <div class="col-lg-6 mb-4 animate__animated animate__fadeInLeft animate__fast">
                <div class="about-card card p-4 h-100">
                    <div class="about-icon"><i class='bx bxs-book-open'></i></div>
                    <h4 class="section-subtitle">Sejarah Singkat</h4>
                    <p>Desa Ngunut adalah Desa batas Kecamatan Dander sebelah Barat dengan  Kecamatan Ngasem. Desa Ngunut memiliki Legenda terbentuknya. Diceritakan bahwa pada zaman dahulu kala ada seorang pertapa tua yang menjadi panutan Masyaratakat yang bernama Eyang Citro Suto. Beliau memiliki pengikut setia yang bernama Pangeran Joko Slining, dan Roro Kembang Sore. Sampai masa kini situs pertapaan Eyang Citro Suto, Pangeran Joko Slining, dan Roro Kembang Sore masih dapat dilihat sampai sekarang. Situs Pertapaan Eyang Citro Suto berada di sebelah tenggara sendang Grogolan. Sementara Joko Slining situs pertapaannya berada di sebelah barat Sendang Grogolan, dan Situs Pertapaan Roro Kembang Sore berada di Sebelah Barat Laut Sendang Grogolan.<a href="https://ngunut-bjn.desa.id/artikel/2020/10/7/sejarah-desa-ngunut" target="_blank" class="about-link">Baca selengkapnya</a></p>
                </div>
            </div>
            <div class="col-lg-6 mb-4 animate__animated animate__fadeInRight animate__fast">
                <div class="about-card card p-4 h-100">
                    <div class="about-icon"><i class='bx bxs-bullseye'></i></div>
                    <h4 class="section-subtitle">Visi & Misi</h4>
                    <p><b>Visi:</b> MENINGKATKAN KESEJAHTERAAN MASYARAKAT MELALUI PEMBANGUNAN YANG PRODUKTIF</p>
                    <b>Misi:</b>
                    <ul>
                        <li>MENYELENGGARAKAN PEMERINTAHAN YANG TRANSPARAN, AKUNTABILITAS, PARTISIPATIF DAN RESPONSIF</li>
                        <li>MENINGKATKAN SUMBER PENDAPATAN DESA MELALUI PENGEMBANGAN POTENSI-POTENSI DESA</li>
                        <li>MENINGKATKAN DAN MEMBERDAYAKAN PERAN WANITA DAN PEMUDA.</li>
                        <li>MENINGKATKAN SDM (SUMBER DAYA MANUSIA) MELALUI PELATIHAN-PELATIHAN KREATIF</li>
                        <li>MENINGKATKAN PENDAPATAN MASYARAKAT MELALUI PETERNAKAN DAN PERIKANAN</li>
                        <li>MENGEMBANGKAN WISATA DESA</li>
                        <li>MENGEMBANGKAN UNIT-UNIT USAHA BUMDES</li>
                    </ul>
                    <a href="https://ngunut-bjn.desa.id/artikel/2016/8/24/visi-dan-misi" target="_blank" class="about-link">Lihat detail visi-misi</a>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-lg-6 mb-4 animate__animated animate__fadeInLeft animate__delay-1s">
                <div class="about-card card p-4 h-100">
                    <div class="about-icon"><i class='bx bxs-bar-chart-alt-2'></i></div>
                    <h4 class="section-subtitle">Statistik Singkat</h4>
                    <div class="mb-2">
                        <span class="about-badge"><i class='bx bxs-group'></i> 2.500+ Penduduk</span>
                        <span class="about-badge"><i class='bx bxs-home'></i> 23 RT / 4 RW</span>
                        <span class="about-badge"><i class='bx bxs-map-pin'></i> 350 Ha</span>
                    </div>
                    <ul>
                        <li><b>Mayoritas Mata Pencaharian:</b> Petani, Pedagang, PNS</li>
                        <li><b>Fasilitas Umum:</b> Balai Desa, Sekolah, Masjid, Lapangan, Posyandu</li>
                    </ul>
                    <a href="https://ngunut-bjn.desa.id/first/statistik/4" target="_blank" class="about-link">Statistik detail</a>
                </div>
            </div>
            <div class="col-lg-6 mb-4 animate__animated animate__fadeInRight animate__delay-1s">
                <div class="about-card card p-4 h-100">
                    <div class="about-icon"><i class='bx bxs-map'></i></div>
                    <h4 class="section-subtitle">Lokasi & Kontak</h4>
                    <p><b>Alamat:</b> Jalan Raya Kayangan Api KM. 3 Bojonegoro, Kode Pos: 62171<br>
                    <b>Email:</b> <a href="mailto:ngunutproduktif@gmail.com">ngunutproduktif@gmail.com</a></p>
                    
                    <!-- Maps Carousel Section -->
                    <div id="mapsCarousel" class="carousel slide my-3" data-bs-ride="carousel" data-bs-interval="10000">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#mapsCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Wilayah Desa"></button>
                            <button type="button" data-bs-target="#mapsCarousel" data-bs-slide-to="1" aria-label="Kantor Desa"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="text-center mb-2">
                                    <div class="about-icon mb-2"><i class='bx bxs-map'></i></div>
                                    <div class="fw-bold">Wilayah Desa Ngunut</div>
                                </div>
                                <div class="map-responsive">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d32485.065439964095!2d111.76800599085654!3d-7.257906247195938!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e79d59fdfcb02f1%3A0xd085b45a4718af8f!2sNgunut%2C%20Kec.%20Dander%2C%20Kabupaten%20Bojonegoro%2C%20Jawa%20Timur!5e1!3m2!1sid!2sid!4v1753443639871!5m2!1sid!2sid" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    </iframe>
                                    <div class="map-fallback" style="display:none;">
                                        <i class='bx bxs-map' style="font-size:3rem; color:#0d6efd;"></i>
                                        <h5 class="mt-3">Wilayah Desa Ngunut</h5>
                                        <p class="text-muted">Kecamatan Dander, Kabupaten Bojonegoro, Jawa Timur</p>
                                        <a href="https://maps.google.com/?q=Ngunut,Dander,Bojonegoro" target="_blank" class="btn btn-outline-primary btn-sm">Buka di Google Maps</a>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="text-center mb-2">
                                    <div class="about-icon mb-2"><i class='bx bxs-building-house'></i></div>
                                    <div class="fw-bold">Kantor/Balai Desa Ngunut</div>
                                </div>
                                <div class="map-responsive">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d879180.5683821469!2d110.8359506!3d-7.6470371!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e79d56f93a3acd5%3A0x51295ab2ffed87ec!2sBalai%20Desa%20Ngunut!5e1!3m2!1sid!2sid!4v1753443576810!5m2!1sid!2sid" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    </iframe>
                                    <div class="map-fallback" style="display:none;">
                                        <i class='bx bxs-building-house' style="font-size:3rem; color:#0d6efd;"></i>
                                        <h5 class="mt-3">Kantor Desa Ngunut</h5>
                                        <p class="text-muted">Jalan Raya Kayangan Api KM. 3 Bojonegoro</p>
                                        <a href="https://maps.google.com/?q=Balai+Desa+Ngunut" target="_blank" class="btn btn-outline-primary btn-sm">Buka di Google Maps</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#mapsCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#mapsCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <a href="https://ngunut-bjn.desa.id/artikel/2013/7/29/kontak-kami" target="_blank" class="about-link">Kontak Lengkap</a>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-lg-6 mb-4 animate__animated animate__fadeInLeft animate__delay-2s">
                <div class="about-card card p-4 h-100">
                    <div class="about-icon"><i class='bx bxs-photo-album'></i></div>
                    <h4 class="section-subtitle">Galeri & Prestasi</h4>
                    <p>Lihat dokumentasi kegiatan, festival, dan prestasi desa di galeri foto.</p>
                    <a href="https://ngunut-bjn.desa.id/index/14" target="_blank" class="btn btn-outline-primary btn-sm">Lihat Galeri</a>
                </div>
            </div>
            <div class="col-lg-6 mb-4 animate__animated animate__fadeInRight animate__delay-2s">
                <div class="about-card card p-4 h-100">
                    <div class="about-icon"><i class='bx bxs-calendar-event'></i></div>
                    <h4 class="section-subtitle">Agenda & Berita Terkini</h4>
                    <ul>
                        <li><a href="https://ngunut-bjn.desa.id/artikel/2025/5/16/pemdes-ngunut-melaksanakan-kegiatan-bbgrm-untuk-menumbuhkan-semangat-gotong-royong" target="_blank">BBGRM: Gotong Royong 2025</a></li>
                        <li><a href="https://ngunut-bjn.desa.id/artikel/2025/4/17/pkk-desa-ngunut-mengadakan-pertemuan-rutin-pkk-dan-halal-bihalal" target="_blank">PKK & Halal Bihalal</a></li>
                        <li><a href="https://ngunut-bjn.desa.id/artikel/2025/2/11/hari-ini-kepala-desa-ngunut-resmi-melantik-ketua-rt-dan-rw-se-desa-ngunut" target="_blank">Pelantikan RT/RW</a></li>
                    </ul>
                    <a href="https://ngunut-bjn.desa.id/index/14" target="_blank" class="btn btn-outline-primary btn-sm">Berita Lainnya</a>
                </div>
            </div>
        </div>
        <div class="text-center">
            <a href="homepage.php" class="btn btn-back animate__animated animate__fadeInUp animate__delay-3s"><i class='bx bx-arrow-back'></i> Kembali ke Beranda</a>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fallback untuk maps jika tidak bisa dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const iframes = document.querySelectorAll('iframe');
            iframes.forEach(iframe => {
                iframe.addEventListener('error', function() {
                    this.style.display = 'none';
                    const fallback = this.nextElementSibling;
                    if (fallback && fallback.classList.contains('map-fallback')) {
                        fallback.style.display = 'block';
                    }
                });
            });
        });
    </script>
</body>
</html> 