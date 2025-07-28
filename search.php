<?php

$csvDir = __DIR__ . '/asset/file/';
$csvFiles = glob($csvDir . 'DAFTAR BIODATA PENDUDUK RT.*.csv');

$searchNik = isset($_POST['nik']) ? trim($_POST['nik']) : '';
$result = null;

foreach ($csvFiles as $csvFile) {
    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        // Lewati header (asumsi header di baris ke-6, data mulai baris ke-7)
        for ($i = 0; $i < 6; $i++) fgetcsv($handle);
        while (($data = fgetcsv($handle)) !== FALSE) {
            // NO.KTP = kolom ke-16 (indeks 15)
            if (isset($data[15]) && trim($data[15]) === $searchNik) {
                $result = $data;
                break 2;
            }
        }
        fclose($handle);
    }
}

header('Content-Type: application/json');
if ($result) {
    echo json_encode(['status' => 'found', 'data' => $result]);
} else {
    echo json_encode(['status' => 'not_found']);
}
?>
