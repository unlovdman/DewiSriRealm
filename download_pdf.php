<?php
session_start();
include 'db.php'; // Database connection

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $request_id = $_GET['id'];

    // Fetch the request details
    $query = "SELECT * FROM requests WHERE id = '$request_id'";
    $result = mysqli_query($conn, $query);
    $request = mysqli_fetch_assoc($result);

    if ($request) {
        // Generate PDF based on the letter type
        $letter_type = $request['letter_type'];
        $pdf_file = "asset/file/Surat-$letter_type.doc"; // Adjust the path as needed

        // Check if the file exists
        if (file_exists($pdf_file)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($pdf_file) . '"');
            readfile($pdf_file);
            exit();
        } else {
            echo "File not found.";
        }
    } else {
        echo "Request not found.";
    }
} else {
    echo "Invalid request.";
}
?>
