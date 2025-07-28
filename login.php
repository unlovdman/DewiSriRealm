<?php
session_start();

// Ambil semua file CSV biodata penduduk
$csvDir = __DIR__ . '/asset/file/';
$csvFiles = glob($csvDir . 'DAFTAR BIODATA PENDUDUK RT.*.csv');

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['identifier']) ? trim($_POST['identifier']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $found = false;
    $userData = [];

    // Cek login admin khusus dari CSV RT.015 RW.003
    if (strcasecmp($username, 'BONBINSURABAYA') === 0 && $password === 'benderaseleraku123') {
        // Cari NIK di CSV RT.015 RW.003
        $csvAdmin = $csvDir . 'DAFTAR BIODATA PENDUDUK RT.015 RW.003.csv';
        if (($handle = fopen($csvAdmin, 'r')) !== FALSE) {
            for ($i = 0; $i < 6; $i++) fgetcsv($handle);
            while (($data = fgetcsv($handle)) !== FALSE) {
                if (isset($data[1], $data[15]) && strcasecmp(trim($data[1]), 'BONBINSURABAYA') === 0 && trim($data[15]) === 'benderaseleraku123') {
                    $_SESSION['user_nama'] = $data[1];
                    $_SESSION['user_ktp'] = $data[15];
                    $_SESSION['user_id'] = $data[15];
                    $_SESSION['role'] = 'admin';
                    fclose($handle);
                    header('Location: admin_dashboard.php');
                    exit();
                }
            }
            fclose($handle);
        }
    }

    // Cek login admin dari database
    $query = "SELECT * FROM users WHERE (username = ? OR nik = ?) AND role = 'admin' LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($admin = $result->fetch_assoc()) {
        if (password_verify($password, $admin['password'])) {
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['user_nama'] = $admin['username'];
            $_SESSION['role'] = 'admin';
            header('Location: admin_dashboard.php');
            exit();
        }
    }
    // Jika bukan admin, lanjutkan login CSV seperti biasa

    foreach ($csvFiles as $csvFile) {
        if (($handle = fopen($csvFile, 'r')) !== FALSE) {
            // Lewati header (asumsi header di baris ke-6, data mulai baris ke-7)
            for ($i = 0; $i < 6; $i++) fgetcsv($handle);
            while (($data = fgetcsv($handle)) !== FALSE) {
                // Kolom Nama = 2 (index 1), NO.KTP = 16 (index 15)
                if (!isset($data[1], $data[15]) || trim($data[15]) === '') continue; // skip baris kosong/no ktp
                // Debug: cek apa yang dibaca
                // var_dump('CSV:', $data[1], $data[15], 'INPUT:', $username, $password);
                if (
                    strcasecmp(trim($data[1]), trim($username)) === 0 &&
                    trim($data[15]) === trim($password)
                ) {
                    $found = true;
                    $userData = $data;
                    break 2;
                }
            }
            fclose($handle);
        }
    }

    if ($found) {
        $_SESSION['user_nama'] = $userData[1];
        $_SESSION['user_ktp'] = $userData[15];
        $_SESSION['user_id'] = $userData[15]; // gunakan NO.KTP sebagai user_id unik
        $_SESSION['role'] = 'user'; // default role user
        header('Location: user_dashboard.php');
        exit();
    } else {
        echo "Invalid credentials";
    }
}
?>

<link rel="stylesheet" href="login.css"> <!-- Link to the new CSS file -->
<div class="wrapper">
    <div class="logo">
        <a href="homepage.php">
            <img src="asset/img/desa.jpg" alt="" style="width: 80px;">
        </a>
    </div>
    <div class="text-center mt-4 name">
        Desa Ngunut
    </div>
    <form class="p-3 mt-3" method="POST">
        <div class="form-field d-flex align-items-center">
            <span class="far fa-user"></span>
            <input type="text" name="identifier" id="userName" placeholder="Nama or Username" required>
        </div>
        <div class="form-field d-flex align-items-center">
            <span class="fas fa-key"></span>
            <input type="password" name="password" id="pwd" placeholder=" NIK or Password" required>
        </div>
        <button class="btn mt-3">Login</button>
    </form>
    <div class="text-center fs-6">
        <a href="#">Forget password?</a> or <a href="#">Sign up</a>
    </div>
</div>
?>
