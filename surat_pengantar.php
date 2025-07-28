<?php
session_start();
require 'vendor/autoload.php';
require 'db.php';

use Dompdf\Dompdf;

// Buat folder temp jika belum ada
$tempDir = 'temp/';
if (!file_exists($tempDir)) {
    mkdir($tempDir, 0777, true);
}

// Di awal file, tambahkan kode untuk membaca dan encode gambar
$logoPath = 'asset/img/desa.jpg';
if (file_exists($logoPath)) {
    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
    $data = file_get_contents($logoPath);
    $logoData = base64_encode($data);
} else {
    error_log("Logo file not found at: " . $logoPath);
    $logoData = '';
}

// Ambil data user dari CSV berdasarkan session user_id (NO.KTP)
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$csvDir = __DIR__ . '/asset/file/';
$csvFiles = glob($csvDir . 'DAFTAR BIODATA PENDUDUK RT.*.csv');
$userData = null;
foreach ($csvFiles as $csvFile) {
    if (($handle = fopen($csvFile, 'r')) !== FALSE) {
        for ($i = 0; $i < 6; $i++) fgetcsv($handle);
        while (($data = fgetcsv($handle)) !== FALSE) {
            if (isset($data[15]) && trim($data[15]) === $_SESSION['user_id']) {
                $userData = $data;
                break 2;
            }
        }
        fclose($handle);
    }
}
if (!$userData) {
    echo '<div class="alert alert-danger">Data tidak ditemukan di CSV.</div>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data tabel
    $jenisSuratArr = isset($_POST['jenis_surat']) ? $_POST['jenis_surat'] : [];
    $banyaknyaArr = isset($_POST['banyaknya']) ? $_POST['banyaknya'] : [];
    $keteranganArr = isset($_POST['keterangan']) ? $_POST['keterangan'] : [];
    $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : $tanggal;

    // Ambil NIK dari POST atau data user CSV
    $nik = $userData[15];
    $nama_pengguna = $userData[1] ?? '';
    $userId = null; // login CSV, tidak ada user_id dari tabel users
    $query = "INSERT INTO letter_history (user_id, letter_type, nik, nama_pengguna) VALUES (?, 'Surat Pengantar', ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $userId, $nik, $nama_pengguna);
    $stmt->execute();
    $lastId = $stmt->insert_id;

    // Generate HTML PDF sesuai template gambar
    $bulanIndo = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
    $hari = date('d');
    $bulan = date('m');
    $tahun = date('Y');
    $tanggalIndo = $hari . ' ' . $bulanIndo[$bulan] . ' ' . $tahun;

    $html = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Surat Pengantar</title>
        <style>
            body { font-family: 'Bookman Old Style', Bookman, serif; font-size: 11pt; margin: 40px; }
            .header { text-align: center; margin-bottom: 20px; position: relative; }
            .header img { width: 80px; height: auto; position: absolute; left: 0; top: 0; }
            .header-text { text-align: center; margin-left: auto; margin-right: auto; font-size: 12pt; line-height: 1.3; }
            .header-text h3 { margin: 2px 0; font-size: 14pt; font-weight: bold; }
            .header-text p { margin: 2px 0; font-size: 11pt; }
            .border-bottom { border-bottom: 3px solid black; margin-bottom: 20px; padding-bottom: 10px; clear: both; }
            .kepada { text-align: right; margin-top: 60px; margin-bottom: 40px; }
            .kepada span { display: block; }
            .kepada .tempat { text-decoration: underline; }
            .title { text-align: center; font-weight: bold; font-size: 14pt; margin: 30px 0 10px 0; text-decoration: underline; }
            .nomor { text-align: center; margin-bottom: 20px; }
            .nomor span { display: inline-block; min-width: 80px; }
            .content { margin: 20px 0; text-align: justify; }
            table.surat { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            table.surat th, table.surat td { border: 1px solid #000; padding: 8px 8px; font-size: 11pt; vertical-align: top; }
            .signature { margin-top: 60px; text-align: right; page-break-inside: avoid; }
        </style>
    </head>
    <body>
        <div class='header'>
            <img src='data:image/jpeg;base64," . $logoData . "' alt='Logo Desa' style='width: 80px; height: auto; position: absolute; left: 0; top: 0;'>
            <div class='header-text'>
                <h3>PEMERINTAH KABUPATEN BOJONEGORO</h3>
                <h3>KECAMATAN DANDER</h3>
                <h3>DESA NGUNUT</h3>
                <p><i>Alamat:Jalan Raya Kayangan Api KM. 3 Bojonegoro, Kode Pos:62171</i></p>
                <p>E-mail: <a href='mailto:ngunutproduktif@gmail.com'>ngunutproduktif@gmail.com</a> 
                Website: <a href='https://ngunut-bjn.desa.id' target='_blank'>ngunut-bjn.desa.id</a></p>
            </div>
            <div class='border-bottom'></div>
        </div>
        <div class='kepada'>
            <span>Kepada Yth.</span>
            <span>Bpk. Camat Dander</span>
            <span class='tempat'>di Tempat</span>
        </div>
        <div class='title'>SURAT PENGANTAR</div>
        <div class='nomor' style='text-align:center; margin-bottom:20px;'>Nomor: 900/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/412.414.01/2025</div>
        <div class='content'>       
            <table class='surat'>
                <thead>
                    <tr>
                        <th style='width:40px;'>No</th>
                        <th style='width:250px;'>Jenis Surat yang dikirim</th>
                        <th style='width:120px;'>Banyaknya</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>";
    for ($i = 0; $i < count($jenisSuratArr); $i++) {
        $no = $i + 1;
        $jenis = htmlspecialchars($jenisSuratArr[$i]);
        $banyak = htmlspecialchars($banyaknyaArr[$i]);
        $ket = htmlspecialchars($keteranganArr[$i]);
        $html .= "<tr><td>$no.</td><td>$jenis</td><td>$banyak</td><td>$ket</td></tr>";
    }
    $html .= "</tbody></table>
        </div>
        <div class='signature'>
            <p>Ngunut, $tanggalIndo</p>
            <p>Kepala Desa Ngunut</p>
            <p></p>
            <p></p>
            <p></p>
            <p>SUWARNO</p>
        </div>
    </body>
    </html>";

    // Generate PDF
    $dompdf = new Dompdf([
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
        'defaultFont' => 'Arial'
    ]);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $pdfContent = $dompdf->output();
    $timestamp = time();
    $filename = "Surat_Pengantar_{$timestamp}.pdf";
    $filepath = $tempDir . $filename;
    if (file_put_contents($filepath, $pdfContent) === false) {
        echo "<div class='alert alert-danger'>Gagal menyimpan file PDF</div>";
    } else {
        // Update pdf_filename di letter_history
        $update = $conn->prepare("UPDATE letter_history SET pdf_filename=? WHERE id=?");
        $update->bind_param("si", $filename, $lastId);
        $update->execute();
        echo "<script>window.open('temp/" . $filename . "', '_blank');alert('Surat berhasil dibuat. Silakan cek tab baru untuk melihat dan mencetak surat.');</script>";
    }

    // Cek riwayat cetak surat
    if (isset($_SESSION['user_id']) && isset($_POST['nik'])) {
        $userId = $_SESSION['user_id'];
        $nik = $_POST['nik'];
        
        $query = "SELECT * FROM letter_history WHERE user_id = ? AND nik = ? AND letter_type = 'Surat Pengantar'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $userId, $nik);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<div class='alert alert-info'>
                    Anda sudah pernah mencetak Surat Pengantar sebelumnya dengan NIK ini.
                    <br>
                    <a href='user_dashboard.php' class='btn btn-primary mt-2'>Kembali ke Menu Utama</a>
                    <a href='#' class='btn btn-success mt-2' onclick='window.open(\"temp/" . $filename . "\", \"_blank\")'>Cetak Ulang</a>
                  </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Surat Pengantar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        body { 
            background-color: #f8f9fa; 
            padding: 20px;
        }
        
        .form-container { 
            max-width: 800px; 
            margin: 30px auto; 
            padding: 30px; 
            background-color: #ffffff; 
            border-radius: 10px; 
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .page-title {
            color: #0d6efd;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .form-label {
            font-weight: 500;
        }
        
        .btn-container {
            margin-top: 30px;
            text-align: center;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 20px;
            font-weight: 500;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .help-tooltip {
          display: inline-block;
          margin-left: 6px;
          cursor: pointer;
          transition: transform 0.15s cubic-bezier(.4,2,.6,1), box-shadow 0.15s;
          box-shadow: 0 2px 8px #2196f344;
          border-radius: 50%;
        }
        .help-tooltip:hover, .help-tooltip:focus {
          transform: scale(1.12) rotate(-4deg);
          box-shadow: 0 4px 16px #0d6efd55;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="header-container">
            <a href="user_dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Menu
            </a>
            <h1 class="page-title">Form Surat Pengantar</h1>
        </div>
        
        <?php
        $nama = $userData[1];
        $tempatLahir = $userData[4];
        $tanggalLahir = $userData[5];
        $jenisKelamin = $userData[2];
        $statusPerkawinan = $userData[3];
        $pekerjaan = $userData[8];
        $agama = $userData[6];
        $nik = $userData[15];
        $alamat = $userData[11] . ' RT ' . $userData[12] . '/RW ' . $userData[13];
        $bulanIndo = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        $hari = date('d');
        $bulan = date('m');
        $tahun = date('Y');
        $tanggal = $hari . ' ' . $bulanIndo[$bulan] . ' ' . $tahun;
        ?>
        <form action="" method="POST" id="pengantarForm" autocomplete="off">
            <div class="mb-4">
                <label class="form-label">Nama Lengkap:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Nama sesuai KTP, otomatis dari data Anda">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($nama) ?>" readonly tabindex="-1">
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Tempat Lahir:
                        <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Tempat lahir sesuai KTP, otomatis dari data Anda">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                                <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                                <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                            </svg>
                        </span>
                    </label>
                    <input type="text" class="form-control" name="tempat_lahir" value="<?= htmlspecialchars($tempatLahir) ?>" readonly tabindex="-1">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Lahir:
                        <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Tanggal lahir sesuai KTP, otomatis dari data Anda">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                                <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                                <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                            </svg>
                        </span>
                    </label>
                    <input type="text" class="form-control" name="tanggal_lahir" value="<?= htmlspecialchars($tanggalLahir) ?>" readonly tabindex="-1">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Jenis Kelamin:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Jenis kelamin sesuai KTP, otomatis dari data Anda">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <input type="text" class="form-control" name="jenis_kelamin" value="<?= htmlspecialchars($jenisKelamin) ?>" readonly tabindex="-1">
            </div>
            <div class="mb-4">
                <label class="form-label">Status Perkawinan:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Status perkawinan sesuai KTP, otomatis dari data Anda">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <input type="text" class="form-control" name="status_perkawinan" value="<?= htmlspecialchars($statusPerkawinan) ?>" readonly tabindex="-1">
            </div>
            <div class="mb-4">
                <label class="form-label">Pekerjaan:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Pekerjaan sesuai KTP, otomatis dari data Anda">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <input type="text" class="form-control" name="pekerjaan" value="<?= htmlspecialchars($pekerjaan) ?>" readonly tabindex="-1">
            </div>
            <div class="mb-4">
                <label class="form-label">Agama:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Agama sesuai KTP, otomatis dari data Anda">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <input type="text" class="form-control" name="agama" value="<?= htmlspecialchars($agama) ?>" readonly tabindex="-1">
            </div>
            <div class="mb-4">
                <label class="form-label">NIK:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="NIK sesuai KTP, otomatis dari data Anda">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <input type="text" class="form-control" name="nik" value="<?= htmlspecialchars($nik) ?>" readonly tabindex="-1">
            </div>
            <div class="mb-4">
                <label class="form-label">Alamat:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Alamat sesuai KTP, otomatis dari data Anda">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <textarea class="form-control" name="alamat" rows="3" readonly tabindex="-1"><?= htmlspecialchars($alamat) ?></textarea>
            </div>
            <div class="mb-4">
                <label class="form-label">Jenis Surat yang Dikirim:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Jenis surat yang akan dikirim (klik + untuk menambah baris)">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <div id="jenis-surat-container">
                    <div class="row mb-2 jenis-surat-row">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="jenis_surat[]" placeholder="Contoh: Surat Keterangan" required pattern="^[^<>\"'&]+$">
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" name="banyaknya[]" placeholder="Jumlah" required min="1">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="keterangan[]" placeholder="Keterangan" required pattern="^[^<>\"'&]+$">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-row">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm mt-2" id="add-row">
                    <i class="fas fa-plus"></i> Tambah Baris
                </button>
            </div>
            <div class="mb-4">
                <label class="form-label">Tanggal:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Tanggal pembuatan surat, otomatis terisi hari ini">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <input type="text" class="form-control" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>" readonly tabindex="-1">
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-primary" style="font-size:1.1em; padding:12px 28px;">
                    <i class="fas fa-print"></i> Cetak Surat Pengantar
                </button>
                <a href="user_dashboard.php" class="btn btn-secondary" style="font-size:1.1em; padding:12px 28px;">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>

    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                calendarWeeks: true,
                clearBtn: true,
                disableTouchKeyboard: true,
                orientation: 'auto top'
            });
        });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.forEach(function (tooltipTriggerEl) {
    new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
</script>
<script>
// Custom pesan required bahasa Indonesia
const form = document.querySelector('form');
if(form) {
  form.addEventListener('submit', function(e) {
    let valid = true;
    form.querySelectorAll('[required]').forEach(function(input) {
      if (!input.value.trim()) {
        input.setCustomValidity('Mohon isi bidang ini!');
        valid = false;
      } else {
        input.setCustomValidity('');
      }
    });
    if (!valid) {
      // biar browser tampilkan pesan custom
      form.reportValidity();
      e.preventDefault();
    }
  });
  form.querySelectorAll('[required]').forEach(function(input) {
    input.addEventListener('input', function() {
      input.setCustomValidity('');
    });
  });
}
</script>
<script>
// Fungsi untuk menambah baris
document.addEventListener('DOMContentLoaded', function() {
    const addRowBtn = document.getElementById('add-row');
    const container = document.getElementById('jenis-surat-container');
    
    if (addRowBtn && container) {
        addRowBtn.addEventListener('click', function() {
            const newRow = document.createElement('div');
            newRow.className = 'row mb-2 jenis-surat-row';
            newRow.innerHTML = `
                <div class="col-md-4">
                    <input type="text" class="form-control" name="jenis_surat[]" placeholder="Contoh: Surat Keterangan" required pattern="^[^<>\"'&]+$">
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" name="banyaknya[]" placeholder="Jumlah" required min="1">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="keterangan[]" placeholder="Keterangan" required pattern="^[^<>\"'&]+$">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            container.appendChild(newRow);
        });
        
        // Event delegation untuk tombol hapus
        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                const row = e.target.closest('.jenis-surat-row');
                if (container.children.length > 1) {
                    row.remove();
                }
            }
        });
    }
});
</script>
</body>
</html>
