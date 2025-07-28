<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $nik = $_POST['nik'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    // Check if NIK already exists
    $check = $conn->prepare("SELECT id FROM users WHERE nik = ?");
    $check->bind_param("s", $nik);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows > 0) {
        $error = "NIK sudah terdaftar!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (nik, username, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $nik, $username, $hashedPassword, $role);
        
        if ($stmt->execute()) {
            header('Location: manage_users.php');
            exit();
        } else {
            $error = "Gagal menambahkan user!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            animation: slideIn 0.5s ease;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .form-floating {
            margin-bottom: 20px;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
        }
        .btn-submit {
            width: 100%;
            padding: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
        }
        .navigation-buttons {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            gap: 10px;
        }
        .back-btn, .dashboard-btn {
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 15px;
            border-radius: 8px;
        }
        .back-btn:hover {
            transform: translateX(-5px);
            background-color: #0d6efd;
            color: white;
        }
        .dashboard-btn:hover {
            transform: translateX(-5px);
            background-color: #6c757d;
            color: white;
        }
        .back-btn i, .dashboard-btn i {
            font-size: 1.2rem;
        }
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .navigation-buttons {
                position: relative;
                top: 0;
                left: 0;
                margin-bottom: 20px;
                justify-content: center;
            }
            .form-container {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="navigation-buttons">
        <a href="manage_users.php" class="btn btn-outline-primary back-btn">
            <i class='bx bx-arrow-back'></i> Back to Users
        </a>
        <a href="admin_dashboard.php" class="btn btn-outline-secondary dashboard-btn">
            <i class='bx bx-home'></i> Back to Dashboard
        </a>
    </div>

    <div class="form-container">
        <h2 class="text-center mb-4"><i class='bx bx-user-plus'></i> Add New User</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger animate__animated animate__shakeX">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="needs-validation" novalidate>
            <div class="form-floating">
                <input type="text" class="form-control" id="username" name="username" 
                       placeholder="Username" required>
                <label for="username">Username</label>
            </div>

            <div class="form-floating">
                <input type="text" class="form-control" id="nik" name="nik" 
                       placeholder="NIK" required pattern="[0-9]{16}">
                <label for="nik">NIK (16 digits)</label>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="Password" required>
                <label for="password">Password</label>
            </div>

            <div class="form-floating">
                <select class="form-select" id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <label for="role">Role</label>
            </div>

            <button type="submit" class="btn btn-primary btn-submit">
                <i class='bx bx-save'></i> Save User
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html> 