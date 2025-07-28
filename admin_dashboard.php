<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Pagination setup
$limit = 10; // Items per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$searchQuery = '';
if (!empty($search)) {
    $searchEsc = mysqli_real_escape_string($conn, $search);
    if ($filter === 'nama') {
        $searchQuery = " WHERE (COALESCE(users.username, letter_history.nama_pengguna) LIKE '%$searchEsc%') ";
    } elseif ($filter === 'nik') {
        $searchQuery = " WHERE (COALESCE(users.nik, letter_history.nik) LIKE '%$searchEsc%') ";
    } elseif ($filter === 'jenis') {
        $searchQuery = " WHERE letter_history.letter_type LIKE '%$searchEsc%' ";
    } elseif ($filter === 'tanggal') {
        $searchQuery = " WHERE DATE_FORMAT(letter_history.created_at, '%d-%m-%Y %H:%i') LIKE '%$searchEsc%' ";
    } else { // all
        $searchQuery = " WHERE (COALESCE(users.username, letter_history.nama_pengguna) LIKE '%$searchEsc%' OR COALESCE(users.nik, letter_history.nik) LIKE '%$searchEsc%' OR letter_history.letter_type LIKE '%$searchEsc%' OR DATE_FORMAT(letter_history.created_at, '%d-%m-%Y %H:%i') LIKE '%$searchEsc%') ";
    }
}

// Count total records for pagination
$countQuery = "SELECT COUNT(*) as total FROM letter_history 
               LEFT JOIN users ON letter_history.user_id = users.id" . $searchQuery;
$countResult = mysqli_query($conn, $countQuery);
$total = mysqli_fetch_assoc($countResult)['total'];
$pages = ceil($total / $limit);

// Fetch letter history with user details
$query = "SELECT letter_history.*, letter_history.nik AS nik_history, users.username, users.nik AS nik_user, 
          DATE_FORMAT(letter_history.created_at, '%d-%m-%Y %H:%i') as formatted_date 
          FROM letter_history 
          LEFT JOIN users ON letter_history.user_id = users.id" 
          . $searchQuery . 
          " ORDER BY letter_history.created_at DESC LIMIT $start, $limit";
$history = mysqli_query($conn, $query);

// Handler delete selected dan delete all
if (isset($_POST['delete_selected']) && !empty($_POST['selected_ids'])) {
    $ids = $_POST['selected_ids'];
    $in = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $stmt = $conn->prepare("DELETE FROM letter_history WHERE id IN ($in)");
    $stmt->bind_param($types, ...$ids);
    if ($stmt->execute()) {
        echo "<script>alert('Selected history deleted!');location.href=window.location.pathname;</script>";
        exit;
    }
}
if (isset($_POST['delete_all'])) {
    $conn->query("DELETE FROM letter_history");
    echo "<script>alert('All history deleted!');location.href=window.location.pathname;</script>";
    exit;
}

// Tambahkan di bagian PHP setelah pagination setup
if (isset($_POST['delete_history'])) {
    $historyId = $_POST['history_id'];
    $deleteQuery = "DELETE FROM letter_history WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $historyId);
    if ($stmt->execute()) {
        echo "<script>alert('History berhasil dihapus!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .dashboard-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .search-box {
            max-width: 300px;
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
        .table th {
            font-weight: 500;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .badge {
            font-size: 0.85em;
            padding: 8px 12px;
        }
        .pagination {
            margin-top: 20px;
            justify-content: center;
        }
        .btn-logout {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .dashboard-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 28px;
            color: white;
        }

        .bg-primary { background: linear-gradient(45deg, #0d6efd, #0a58ca); }
        .bg-success { background: linear-gradient(45deg, #198754, #157347); }
        .bg-info { background: linear-gradient(45deg, #0dcaf0, #0aa2c0); }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .card-text {
            color: #6c757d;
            margin-bottom: 20px;
            min-height: 48px;
        }

        .btn-card {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-card:hover {
            transform: scale(1.02);
        }

        .btn-card i {
            margin-right: 8px;
        }

        /* Animasi untuk cards */
        .dashboard-card {
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
        }

        .dashboard-card:nth-child(1) { animation-delay: 0.1s; }
        .dashboard-card:nth-child(2) { animation-delay: 0.2s; }
        .dashboard-card:nth-child(3) { animation-delay: 0.3s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <a href="homepage.php" class="btn btn-danger btn-logout">
        <i class='bx bx-log-out'></i> Logout
    </a>

    <div class="container dashboard-container">
        <div class="dashboard-header">
            <h2><i class='bx bxs-dashboard'></i> Admin Dashboard</h2>
            <form class="search-box" method="GET">
                <div class="input-group">
                    <span class="input-group-text" style="font-size:1em; background:#e9ecef;">Filter by:</span>
                    <select class="form-select" name="filter" style="max-width:140px;" data-bs-toggle="tooltip" title="Pilih kolom untuk pencarian spesifik">
                        <option value="all" <?php if($filter=='all') echo 'selected'; ?>>Semua</option>
                        <option value="nama" <?php if($filter=='nama') echo 'selected'; ?>>Nama</option>
                        <option value="nik" <?php if($filter=='nik') echo 'selected'; ?>>NIK</option>
                        <option value="jenis" <?php if($filter=='jenis') echo 'selected'; ?>>Jenis Surat</option>
                        <option value="tanggal" <?php if($filter=='tanggal') echo 'selected'; ?>>Tanggal Cetak</option>
                    </select>
                    <input type="text" class="form-control" placeholder="Cari surat..." 
                           name="search" value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class='bx bx-search'></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="card-icon bg-primary">
                            <i class='bx bxs-user-account'></i>
                        </div>
                        <h5 class="card-title">Manage Users</h5>
                        <p class="card-text">Kelola semua pengguna sistem, termasuk admin dan user biasa.</p>
                        <a href="manage_users.php" class="btn btn-primary btn-card">
                            <i class='bx bx-user'></i> Manage Users
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="card-icon bg-success">
                            <i class='bx bx-user-plus'></i>
                        </div>
                        <h5 class="card-title">Add New User</h5>
                        <p class="card-text">Tambahkan pengguna baru ke dalam sistem.</p>
                        <a href="add_user.php" class="btn btn-success btn-card">
                            <i class='bx bx-plus'></i> Add User
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="card-icon bg-info">
                            <i class='bx bx-history'></i>
                        </div>
                        <h5 class="card-title">Letter History</h5>
                        <p class="card-text">Lihat dan kelola riwayat pembuatan surat.</p>
                        <button class="btn btn-info btn-card" onclick="scrollToHistory()">
                            <i class='bx bx-time'></i> View History
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" id="historyForm">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>No</th>
                        <th>Nama Pengguna</th>
                        <th>NIK</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal Cetak</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $number = ($page - 1) * $limit + 1;
                    while ($row = mysqli_fetch_assoc($history)): 
                    ?>
                        <tr>
                            <td><input type="checkbox" name="selected_ids[]" value="<?php echo $row['id']; ?>"></td>
                            <td><?php echo $number++; ?></td>
                            <td>
                            <?php
                                if (!empty($row['username'])) {
                                    echo htmlspecialchars($row['username']);
                                } elseif (!empty($row['nama_pengguna'])) {
                                    echo htmlspecialchars($row['nama_pengguna']);
                                } elseif (!empty($row['nik'])) {
                                    echo 'User CSV (NIK: ' . htmlspecialchars($row['nik']) . ')';
                                } else {
                                    echo 'User CSV';
                                }
                            ?>
                            </td>
                            <td><?php echo isset($row['nik_history']) && $row['nik_history'] !== '' ? htmlspecialchars($row['nik_history']) : '-'; ?></td>
                            <td>
                                <span class="badge bg-primary">
                                    <?php echo htmlspecialchars($row['letter_type']); ?>
                                </span>
                            </td>
                            <td><?php echo $row['formatted_date']; ?></td>
                            <td>
                                <?php if (!empty($row['pdf_filename'])): ?>
                                <button class="btn btn-sm btn-info" type="button" onclick="window.open('print_letter.php?id=<?php echo $row['id']; ?>', '_blank')">
                                    <i class='bx bx-printer'></i> Cetak
                                </button>
                                <?php endif; ?>
                                <form method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Are you sure you want to delete this history?');">
                                    <input type="hidden" name="history_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="delete_history" class="btn btn-danger btn-sm btn-action">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="mb-3 d-flex gap-2">
            <button type="submit" name="delete_selected" class="btn btn-danger" onclick="return confirm('Hapus semua riwayat yang dipilih?')">
                <i class='bx bx-trash'></i> Delete Selected
            </button>
            <button type="submit" name="delete_all" class="btn btn-danger" onclick="return confirm('Hapus semua riwayat?')">
                <i class='bx bx-trash'></i> Delete All
            </button>
        </div>
        </form>

        <!-- Pagination -->
        <?php if ($pages > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&filter=<?php echo urlencode($filter); ?>">
                        Previous
                    </a>
                </li>
                
                <?php for($i = 1; $i <= $pages; $i++): ?>
                <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&filter=<?php echo urlencode($filter); ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
                <?php endfor; ?>
                
                <li class="page-item <?php echo $page >= $pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&filter=<?php echo urlencode($filter); ?>">
                        Next
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function printLetter(id) {
            // Implementasi fungsi cetak
            window.open('print_letter.php?id=' + id, '_blank');
        }

        function scrollToHistory() {
            document.querySelector('.table-responsive').scrollIntoView({
                behavior: 'smooth'
            });
        }
    </script>
    <script>
document.getElementById('selectAll').addEventListener('change', function() {
    var checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
    for (var cb of checkboxes) cb.checked = this.checked;
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
</body>
</html>
?>
