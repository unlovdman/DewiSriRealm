<?php
session_start();
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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .profile-card { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); padding: 30px; }
        .profile-icon { font-size: 60px; color: #0d6efd; margin-bottom: 20px; text-align: center; }
        .profile-title { font-size: 1.5rem; font-weight: 600; text-align: center; margin-bottom: 20px; }
        .profile-info { font-size: 1.1rem; }
        .btn-back { margin-top: 30px; }
    </style>
</head>
<body>
    <div class="profile-card">
        <div class="profile-icon"><i class='bx bxs-user'></i></div>
        <div class="profile-title">Profil Pengguna</div>
        <div class="profile-info">
            <?php if ($userData): ?>
                <p><b>Nama:</b> <?php echo htmlspecialchars($userData[1]); ?></p>
                <p><b>NIK:</b> <?php echo htmlspecialchars($userData[15]); ?></p>
                <p><b>Jenis Kelamin:</b> <?php echo htmlspecialchars($userData[2]); ?></p>
                <p><b>Tempat, Tgl Lahir:</b> <?php echo htmlspecialchars($userData[4]); ?>, <?php echo htmlspecialchars($userData[5]); ?></p>
                <p><b>Alamat:</b> <?php echo htmlspecialchars($userData[11]); ?> RT <?php echo htmlspecialchars($userData[12]); ?>/RW <?php echo htmlspecialchars($userData[13]); ?></p>
            <?php else: ?>
                <p><b>Username:</b> <?php echo htmlspecialchars($_SESSION['user_nama'] ?? '-'); ?></p>
                <p><b>NIK:</b> <?php echo htmlspecialchars($_SESSION['user_ktp'] ?? '-'); ?></p>
            <?php endif; ?>
        </div>
        <a href="user_dashboard.php" class="btn btn-primary btn-back"><i class='bx bx-arrow-back'></i> Kembali ke Dashboard</a>
    </div>
</body>
</html> 