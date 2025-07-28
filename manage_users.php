<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Delete User
if (isset($_POST['delete_user'])) {
    $userId = $_POST['user_id'];
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        echo "<script>alert('User berhasil dihapus!');</script>";
    }
}

// Fetch Users
$query = "SELECT * FROM users ORDER BY created_at DESC";
$users = mysqli_query($conn, $query);

// Ambil user dari semua file CSV
$csvDir = __DIR__ . '/asset/file/';
$csvFiles = glob($csvDir . 'DAFTAR BIODATA PENDUDUK RT.*.csv');
$csvUsers = [];
foreach ($csvFiles as $csvFile) {
    if (($handle = fopen($csvFile, 'r')) !== FALSE) {
        for ($i = 0; $i < 6; $i++) fgetcsv($handle);
        while (($data = fgetcsv($handle)) !== FALSE) {
            if (!isset($data[1], $data[15]) || trim($data[15]) === '') continue;
            $csvUsers[] = [
                'username' => $data[1],
                'nik' => $data[15],
                'role' => (strcasecmp($data[1], 'BONBINSURABAYA') === 0 && $data[15] === 'benderaseleraku123') ? 'admin' : 'csv',
                'created_at' => '-',
                'csv_file' => basename($csvFile)
            ];
        }
        fclose($handle);
    }
}

// Search dan Pagination
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$allUsers = [];

// Ambil user dari database
while ($user = mysqli_fetch_assoc($users)) {
    $allUsers[] = [
        'username' => $user['username'],
        'nik' => $user['nik'],
        'role' => $user['role'],
        'created_at' => $user['created_at'],
        'csv_file' => '-',
        'is_db' => true,
        'id' => $user['id']
    ];
}
// Gabungkan dengan user CSV
foreach ($csvUsers as $csvUser) {
    $allUsers[] = [
        'username' => $csvUser['username'],
        'nik' => $csvUser['nik'],
        'role' => $csvUser['role'],
        'created_at' => '-',
        'csv_file' => $csvUser['csv_file'],
        'is_db' => false,
        'id' => null
    ];
}
// Filter search
if ($search !== '') {
    $allUsers = array_filter($allUsers, function($u) use ($search) {
        return stripos($u['username'], $search) !== false || stripos($u['nik'], $search) !== false;
    });
}
$totalUsers = count($allUsers);
$totalPages = ceil($totalUsers / $limit);
$offset = ($page - 1) * $limit;
$allUsers = array_slice($allUsers, $offset, $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            padding: 20px;
        }
        .dashboard-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease-in;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .table thead {
            background-color: #0d6efd;
            color: white;
        }
        .table tbody tr {
            transition: all 0.3s ease;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }
        .btn-action {
            transition: all 0.3s ease;
        }
        .btn-action:hover {
            transform: translateY(-2px);
        }
        .page-title {
            color: #0d6efd;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            animation: slideInDown 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .add-user-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #0d6efd;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }
        .add-user-btn:hover {
            transform: scale(1.1) rotate(90deg);
            background: #0b5ed7;
            color: white;
        }
        .navigation-buttons {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }

        .dashboard-btn {
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 15px;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .dashboard-btn:hover {
            transform: translateX(-5px);
            background-color: #6c757d;
            color: white;
        }

        .dashboard-btn i {
            font-size: 1.2rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .navigation-buttons {
                position: relative;
                top: 0;
                left: 0;
                margin-bottom: 20px;
                display: flex;
                justify-content: center;
            }
            
            .dashboard-container {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="navigation-buttons">
        <a href="admin_dashboard.php" class="btn btn-outline-secondary dashboard-btn">
            <i class='bx bx-home'></i> Back to Dashboard
        </a>
    </div>

    <div class="container dashboard-container">
        <h2 class="page-title"><i class='bx bxs-user-account'></i> Manage Users</h2>
        
        <form class="search-box mb-3" method="GET">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Cari username atau NIK..." name="search" value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-primary" type="submit">
            <i class='bx bx-search'></i>
        </button>
    </div>
</form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>NIK</th>
                        <th>Role</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = $offset + 1;
                    foreach ($allUsers as $user): ?>
                    <tr class="animate__animated animate__fadeIn">
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['nik']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'danger' : ($user['role'] == 'csv' ? 'secondary' : 'primary'); ?>">
                                <?php echo ucfirst($user['role']); ?>
                            </span>
                            <?php if (!$user['is_db']): ?><span class="badge bg-light text-dark">CSV</span><?php endif; ?>
                        </td>
                        <td><?php echo $user['is_db'] ? date('d-m-Y H:i', strtotime($user['created_at'])) : htmlspecialchars($user['csv_file']); ?></td>
                        <td>
                            <?php if ($user['is_db']): ?>
                            <form method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Are you sure you want to delete this user?');">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" name="delete_user" class="btn btn-danger btn-sm btn-action">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </form>
                            <?php else: ?>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Akun CSV tidak bisa dihapus permanen dari sini. Hapus manual di file jika ingin menghapus.');">
                                <button type="button" class="btn btn-secondary btn-sm btn-action" disabled>
                                    <i class='bx bx-block'></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($totalPages > 1): ?>
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
        </li>
        <?php
        $maxLinks = 7;
        $startPage = max(1, $page - 3);
        $endPage = min($totalPages, $page + 3);
        if ($totalPages > $maxLinks) {
            if ($startPage > 2) {
                echo '<li class="page-item"><a class="page-link" href="?page=1&search='.urlencode($search).'">1</a></li>';
                echo '<li class="page-item"><a class="page-link" href="?page=2&search='.urlencode($search).'">2</a></li>';
                if ($startPage > 3) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            for ($i = $startPage; $i <= $endPage; $i++) {
                echo '<li class="page-item '.($page == $i ? 'active' : '').'"><a class="page-link" href="?page='.$i.'&search='.urlencode($search).'">'.$i.'</a></li>';
            }
            if ($endPage < $totalPages - 1) {
                if ($endPage < $totalPages - 2) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                echo '<li class="page-item"><a class="page-link" href="?page='.($totalPages-1).'&search='.urlencode($search).'">'.($totalPages-1).'</a></li>';
                echo '<li class="page-item"><a class="page-link" href="?page='.$totalPages.'&search='.urlencode($search).'">'.$totalPages.'</a></li>';
            }
        } else {
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<li class="page-item '.($page == $i ? 'active' : '').'"><a class="page-link" href="?page='.$i.'&search='.urlencode($search).'">'.$i.'</a></li>';
            }
        }
        ?>
        <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
        </li>
    </ul>
</nav>
<?php endif; ?>

    <a href="add_user.php" class="add-user-btn">
        <i class='bx bx-plus'></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 