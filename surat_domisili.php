<?php
session_start();
require 'vendor/autoload.php';
require 'db.php';

use Dompdf\Dompdf;

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

// Data mapping
$nama = $userData[1];
$tempatLahir = $userData[4];
$tanggalLahir = $userData[5];
$jenisKelamin = $userData[2];
$agama = $userData[6];
$pekerjaan = $userData[8];
$nik = $userData[15];
$alamat = $userData[11] . ' RT ' . $userData[12] . '/RW ' . $userData[13];
// Ganti $tanggal agar bulan berupa teks Indonesia
$bulanIndo = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];
$hari = date('d');
$bulan = date('m');
$tahun = date('Y');
$tanggal = $hari . ' ' . $bulanIndo[$bulan] . ' ' . $tahun;

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
    // Jika file tidak ditemukan, tambahkan log error
    error_log("Logo file not found at: " . $logoPath);
    $logoData = '';
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nik = $userData[15];
    $nama_pengguna = $userData[1];
    $userId = null; // login CSV, tidak ada user_id dari tabel users
    $query = "INSERT INTO letter_history (user_id, letter_type, nik, nama_pengguna) VALUES (?, 'Surat Domisili', ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $userId, $nik, $nama_pengguna);
    $stmt->execute();
    $lastId = $stmt->insert_id;

    // HTML untuk PDF
    $html = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Surat Keterangan Domisili</title>
        <style>
            body { 
                font-family: 'Bookman Old Style', Bookman, serif; 
                margin: 40px; 
                font-size: 10pt;
            }
            .header { 
                text-align: center; 
                margin-bottom: 20px;
                position: relative;
            }
            .header img {
                width: 80px;
                height: auto;
                position: absolute;
                left: 0;
                top: 0;
            }
            .header-text {
                text-align: center;
                margin-left: auto;
                margin-right: auto;
                font-size: 12pt;
                line-height: 1.3;
            }
            .header-text h3 {
                margin: 2px 0;
                font-size: 14pt;
                font-weight: bold;
            }
            .header-text p {
                margin: 2px 0;
                font-size: 11pt;
            }
            .border-bottom {
                border-bottom: 3px solid black;
                margin-bottom: 20px;
                padding-bottom: 10px;
                clear: both;
            }
            .title {
                text-align: center;
                font-weight: bold;
                font-size: 14pt;
                margin: 30px 0;
                text-decoration: underline;
            }
            .content {
                margin: 20px 0;
                text-align: justify;
                line-height: 1.5;
            }
            .indent {
                margin-left: 30px;
            }
            .signature {
                margin-top: 40px;
                text-align: right;
                padding-right: 40px;
            }
            table {
                margin-left: 30px;
                margin-bottom: 20px;
            }
            td {
                padding: 5px 0;
                vertical-align: top;
            }
        </style>
    </head>
    <body>
        <div class='header'>
    <img src='data:image/jpeg;base64,<?= $logoData ?>' alt='Logo Desa'>
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
        <div class='title' style='margin-bottom:0; text-decoration:underline; font-size:20pt; font-weight:900;'>SURAT KETERANGAN DOMISILI</div>
        <div class='no-surat' style='text-align:center; font-size:12pt; font-weight:normal; margin-top:2px; margin-bottom:0;'>No: 470/391/412.414.01/2025</div>
        
        <div class='content'>
            <p>Yang bertanda tangan dibawah ini Kepala Desa Ngunut Kecamatan Dander Kabupaten Bojonegoro, menerangkan dengan sebenarnya bahwa:</p>
            <table>
                <tr>
                    <td width='150'>Nama</td>
                    <td width='10'>:</td>
                    <td>$nama</td>
                </tr>
                <tr>
                    <td>Tempat, Tgl Lahir</td>
                    <td>:</td>
                    <td>$tempatLahir, $tanggalLahir</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>$jenisKelamin</td>
                </tr>
                <tr>
                    <td>Agama</td>
                    <td>:</td>
                    <td>$agama</td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td>$pekerjaan</td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td>$nik</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>Dusun $alamat, Desa Ngunut, Kecamatan Dander, Kabupaten Bojonegoro</td>
                </tr>
                <tr>
                    <td>Keterangan </td>
                    <td>:</td>
                    <td>Orang tersebut diatas benar-benar berdomisili di RT.$userData[12] RW.$userData[13], Desa Ngunut, Kecamatan Dander, Kabupaten Bojonegoro</td>
                </tr>
            </table>

            
            
            <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya</p>
        </div>

        <div class='signature'>
            <p>Ngunut, $tanggal</p>
            <h3>Kepala Desa Ngunut</h3>
            <br><br>
            <h3><u>SUWARNO</u></h3>
        </div>
    </body>
    </html>
    ";

    
    $dompdf = new Dompdf([
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
        'defaultFont' => 'Arial'
    ]);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    try {
        $pdfContent = $dompdf->output();
        $filename = "Surat_Domisili_" . time() . ".pdf";
        $filepath = $tempDir . $filename;
        
        if (file_put_contents($filepath, $pdfContent) === false) {
            throw new Exception("Gagal menyimpan file PDF");
        }
        // Update pdf_filename di letter_history
        $update = $conn->prepare("UPDATE letter_history SET pdf_filename=? WHERE id=?");
        $update->bind_param("si", $filename, $lastId);
        $update->execute();
        echo "<script>
            window.open('temp/" . $filename . "', '_blank');
            alert('Surat berhasil dibuat. Silakan cek tab baru untuk melihat dan mencetak surat.');
        </script>";

    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Terjadi kesalahan: " . $e->getMessage() . "</div>";
    }
}


if (isset($_SESSION['user_id']) && isset($_POST['nik'])) {
    $userId = $_SESSION['user_id'];
    $nik = $_POST['nik'];
    
    $query = "SELECT * FROM letter_history WHERE user_id = ? AND nik = ? AND letter_type = 'Surat Domisili'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $userId, $nik);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<div class='alert alert-info'>
                Anda sudah pernah mencetak Surat Domisili sebelumnya dengan NIK ini.
                <br>
                <a href='user_dashboard.php' class='btn btn-primary mt-2'>Kembali ke Menu Utama</a>
                <a href='#' class='btn btn-success mt-2' onclick='window.open(\"temp/" . $filename . "\", \"_blank\")'>Cetak Ulang</a>
              </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Surat Domisili</title>
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
        
        /* ... style lainnya dari surat_kuasa.php ... */
    </style>
</head>
<body>
    <div class="form-container">
        <div class="header-container">
            <a href="user_dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Menu
            </a>
            <h1 class="page-title">Form Surat Domisili</h1>
        </div>
        <form action="" method="POST" autocomplete="off">
            <div class="mb-4">
                <label class="form-label">Nama Lengkap:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Nama sesuai KTP, otomatis dari data Anda">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($nama); ?>" readonly tabindex="-1">
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
                    <input type="text" class="form-control" name="tempat_lahir" value="<?php echo htmlspecialchars($tempatLahir); ?>" readonly tabindex="-1">
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
                    <input type="text" class="form-control" name="tanggal_lahir" value="<?php echo htmlspecialchars($tanggalLahir); ?>" readonly tabindex="-1">
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
                <input type="text" class="form-control" name="jenis_kelamin" value="<?php echo htmlspecialchars($jenisKelamin); ?>" readonly tabindex="-1">
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
                <input type="text" class="form-control" name="agama" value="<?php echo htmlspecialchars($agama); ?>" readonly tabindex="-1">
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
                <input type="text" class="form-control" name="pekerjaan" value="<?php echo htmlspecialchars($pekerjaan); ?>" readonly tabindex="-1">
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
                <input type="text" class="form-control" name="nik" value="<?php echo htmlspecialchars($nik); ?>" readonly tabindex="-1">
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
                <textarea class="form-control" name="alamat" rows="3" readonly tabindex="-1"><?php echo htmlspecialchars($alamat); ?></textarea>
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
                <input type="text" class="form-control" name="tanggal" value="<?php echo htmlspecialchars($tanggal); ?>" readonly tabindex="-1">
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-primary" style="font-size:1.1em; padding:12px 28px;">
                    <i class="fas fa-print"></i> Cetak Surat Domisili
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
<style>
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
</body>
</html>
