<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    echo 'ID surat tidak ditemukan.';
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM letter_history WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo 'Data surat tidak ditemukan.';
    exit();
}

$pdf = $row['pdf_filename'] ?? '';
if ($pdf && file_exists(__DIR__ . '/temp/' . $pdf)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . basename($pdf) . '"');
    readfile(__DIR__ . '/temp/' . $pdf);
    exit();
} else {
    echo 'File PDF surat tidak ditemukan di folder temp.';
} 