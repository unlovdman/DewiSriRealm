<?php
include 'db.php';


$nik = '3522065705040005';
$username = 'me';
$password = '111'; 
$role = 'admin';

$nik = '3522065705040004';
$username = 'mel';
$password = '111'; 
$role = 'user';

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);


$query = "INSERT INTO users (nik, username, password, role) VALUES ('$nik', '$username', '$hashedPassword', '$role')";


if (mysqli_query($conn, $query)) {
    echo "User created successfully.";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>