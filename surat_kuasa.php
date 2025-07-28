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
// Data mapping pemberi kuasa
$namaPemberi = $userData[1];
$alamatPemberi = $userData[11] . ' RT ' . $userData[12] . '/RW ' . $userData[13];
$ktpPemberi = $userData[15];

// Buat folder temp jika belum ada
$tempDir = 'temp/';
if (!file_exists($tempDir)) {
    mkdir($tempDir, 0777, true);
}

// Di awal file, tambahkan kode untuk membaca dan encode gambar
$logoPath = 'asset/img/desa.jpg';
$type = pathinfo($logoPath, PATHINFO_EXTENSION);
$data = file_get_contents($logoPath);
$base64 = base64_encode($data);

// Proses form jika tombol submit ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Tangkap data dari form
    $namaPenerima = $_POST['nama_penerima'];
    $alamatPenerima = nl2br($_POST['alamat_penerima']);
    $ktpPenerima = $_POST['ktp_penerima'];
    $jabatanPenerima = $_POST['jabatan_penerima'];
    $tanggal = $_POST['tanggal'];

    // Ambil NIK dari POST atau data user CSV
    $nik = $userData[15];
    $nama_pengguna = $userData[1] ?? '';
    $userId = null; // login CSV, tidak ada user_id dari tabel users
    
    $query = "INSERT INTO letter_history (user_id, letter_type, nik, nama_pengguna) VALUES (?, 'Surat Kuasa', ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $userId, $nik, $nama_pengguna);
    $stmt->execute();
    $lastId = $stmt->insert_id;

       // Format HTML untuk PDF
       $html = "
       <!DOCTYPE html>
       <html lang='id'>
       <head>
           <meta charset='UTF-8'>
           <title>Surat Kuasa</title>
           <style>
               body { font-family: 'Bookman Old Style', Bookman, serif; line-height: 1.6; margin: 20px; }
               h2 { text-align: center; text-decoration: underline; }
               .content { margin-top: 20px; }
               .signature { margin-top: 50px; text-align: center; }
               .signature div { display: inline-block; margin: 0 50px; }
               .header { text-align: center; margin-bottom: 20px; }
               .kop-container { 
                   display: table;
                   width: 100%;
                   margin-bottom: 20px;
               }
               .logo-cell { 
                   display: table-cell;
                   width: 100px;
                   vertical-align: middle;
               }
               .text-cell {
                   display: table-cell;
                   vertical-align: middle;
                   text-align: center;
               }
               .logo-img {
                   width: 80px;
                   height: auto;
               }
               .kop-text h3 { margin: 0; }
               .kop-text p { margin: 0; }
               .line { border-top: 2px solid black; margin: 10px 0; }
           </style>
       </head>
       <body>
           
   
           <h2>SURAT KUASA</h2>
           <div class='content'>
               <p>Yang bertanda tangan di bawah ini:</p>
               <p>Nama: <strong>$namaPemberi</strong><br>
               Alamat: <strong>$alamatPemberi</strong><br>
               No. KTP/NIK: <strong>$ktpPemberi</strong></p>
               
               <p>Dengan ini memberi kuasa kepada:</p>
               <p>Nama: <strong>$namaPenerima</strong><br>
               Alamat: <strong>$alamatPenerima</strong><br>
               No. KTP/NIK: <strong>$ktpPenerima</strong><br>
               Jabatan: <strong>$jabatanPenerima</strong></p>
               
               <p>Untuk mengurus/menyelesaikan persyaratan administrasi dan/atau persyaratan teknis permohonan perizinan/non-perizinan surat di Unit Pelaksana PTSP Kelurahan.</p>
               
               <p>Demikian Surat Kuasa ini dibuat agar dapat dipergunakan sebagaimana mestinya.</p>
           </div>
           <div class='signature'>
               <div>
                   <p>Bojonegoro, $tanggal</p>
                   <p><strong>Yang Memberi Kuasa</strong></p>
                   <br><br>
                   <p>______________________</p>
               </div>
               <div>
                   <p><strong>Yang Menerima Kuasa</strong></p>
                   <br><br>
                   <p>______________________</p>
               </div>
           </div>
       </body>
       </html>
       ";

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
    $filename = "Surat_Kuasa_{$timestamp}.pdf";
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
}

// Cek riwayat cetak surat
if (isset($_SESSION['user_id']) && isset($_POST['ktp_pemberi'])) {
    $userId = $_SESSION['user_id'];
    $nik = $_POST['ktp_pemberi'];
    
    $query = "SELECT * FROM letter_history WHERE user_id = ? AND nik = ? AND letter_type = 'Surat Kuasa'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $userId, $nik);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<div class='alert alert-info'>
                Anda sudah pernah mencetak Surat Kuasa sebelumnya dengan NIK ini.
                <br>
                <a href='user_dashboard.php' class='btn btn-primary mt-2'>Kembali ke Menu Utama</a>
                <a href='#' class='btn btn-success mt-2' onclick='window.open(\"temp/" . $filename . "\", \"_blank\")'>Cetak Ulang</a>
              </div>";
    }
}

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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Surat Kuasa</title>
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
            color: #212529;
        }
        
        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 10px 15px;
        }
        
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
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
        
        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        
        .btn-secondary:hover {
            background-color: #5c636a;
            transform: translateY(-2px);
        }
        
        .alert {
            border-radius: 5px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .alert-info {
            background-color: #cfe2ff;
            border-color: #b6d4fe;
            color: #084298;
        }
        
        .input-group {
            margin-bottom: 20px;
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        /* Datepicker styles */
        .datepicker-dropdown {
            padding: 0.5rem !important;
            margin-top: 0.5rem !important;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
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
            <h1 class="page-title">Form Surat Kuasa</h1>
        </div>
        
        <form action="" method="POST" autocomplete="off">
            <div class="mb-4">
                <label class="form-label">Nama Pemberi Kuasa:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Nama sesuai KTP, otomatis dari data Anda">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <input type="text" class="form-control" name="nama_pemberi" value="<?php echo htmlspecialchars($namaPemberi); ?>" readonly tabindex="-1">
            </div>
            
            <div class="mb-4">
                <label class="form-label">Alamat Pemberi Kuasa:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Alamat sesuai KTP, otomatis dari data Anda">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <textarea class="form-control" name="alamat_pemberi" rows="3" readonly tabindex="-1"><?php echo htmlspecialchars($alamatPemberi); ?></textarea>
            </div>
            
            <div class="mb-4">
                <label class="form-label">No. KTP/NIK Pemberi Kuasa:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="NIK sesuai KTP, otomatis dari data Anda">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <input type="text" class="form-control" name="ktp_pemberi" value="<?php echo htmlspecialchars($ktpPemberi); ?>" readonly tabindex="-1">
            </div>
            
            <div class="mb-4">
                <label class="form-label">Nama Penerima Kuasa:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Nama lengkap penerima kuasa">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <input type="text" class="form-control" name="nama_penerima" required pattern="^[^<>\"'&]+$" placeholder="Contoh: BUDI SANTOSO" autocomplete="off" style="font-size:1.1em;">
            </div>
            
            <div class="mb-4">
                <label class="form-label">Alamat Penerima Kuasa:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Masukkan alamat lengkap penerima kuasa sesuai KTP">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <textarea class="form-control" name="alamat_penerima" rows="3" required pattern="^[^<>\"'&]+$" placeholder="Contoh: Jl. Mawar No. 10, RT 02/RW 01" autocomplete="off" style="font-size:1.1em;"></textarea>
            </div>
            
            <div class="mb-4">
                <label class="form-label">No. KTP/NIK Penerima Kuasa:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Masukkan NIK penerima kuasa sesuai KTP (16 digit)">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <input type="text" class="form-control" name="ktp_penerima" required pattern="^[0-9]{16}$" maxlength="16" placeholder="Contoh: 3522064305530004" autocomplete="off" style="font-size:1.1em;">
            </div>
            
            <div class="mb-4">
                <label class="form-label">Jabatan Penerima Kuasa:
                    <span class="help-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover focus" title="Masukkan jabatan penerima kuasa jika ada, boleh dikosongkan jika tidak ada">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;">
                            <circle cx="11" cy="11" r="10" fill="#2196f3"/>
                            <text x="11" y="16" text-anchor="middle" font-size="15" fill="#fff" font-family="Arial" font-weight="bold">?</text>
                        </svg>
                    </span>
                </label>
                <input type="text" class="form-control" name="jabatan_penerima" pattern="^[^<>\"'&]*$" placeholder="Contoh: Anak, Saudara, dll" autocomplete="off" style="font-size:1.1em;">
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
                <div class="input-group">
                    <input type="text" class="form-control datepicker" name="tanggal" value="<?php echo htmlspecialchars($tanggal); ?>" readonly tabindex="-1">
                    <span class="input-group-text">
                        <i class="fas fa-calendar"></i>
                    </span>
                </div>
            </div>
            
            <div class="btn-container">
                <button type="submit" class="btn btn-primary" style="font-size:1.1em; padding:12px 28px;">
                    <i class="fas fa-print"></i> Cetak Surat Kuasa
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
</body>
</html>