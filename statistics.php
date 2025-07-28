<?php
session_start();

// Fungsi untuk membaca semua file CSV dan mengumpulkan statistik
function getVillageStatistics() {
    $csvDir = __DIR__ . '/asset/file/';
    $csvFiles = glob($csvDir . 'DAFTAR BIODATA PENDUDUK RT.*.csv');
    
    $statistics = [
        'total_penduduk' => 0,
        'jenis_kelamin' => ['Laki-laki' => 0, 'Perempuan' => 0],
        'agama' => [],
        'pekerjaan' => [],
        'status_perkawinan' => [],
        'rt_distribution' => [],
        'rw_distribution' => [],
        'usia_distribution' => [
            '0-17' => 0,    // Anak-anak
            '18-25' => 0,   // Remaja
            '26-35' => 0,   // Dewasa Muda
            '36-45' => 0,   // Dewasa
            '46-55' => 0,   // Dewasa Tua
            '56-65' => 0,   // Lansia
            '65+' => 0      // Lansia Tua
        ]
    ];
    
    foreach ($csvFiles as $csvFile) {
        if (($handle = fopen($csvFile, 'r')) !== FALSE) {
            // Lewati header (6 baris pertama)
            for ($i = 0; $i < 6; $i++) {
                fgetcsv($handle);
            }
            
            while (($data = fgetcsv($handle)) !== FALSE) {
                if (!isset($data[1]) || trim($data[1]) === '') continue;
                
                $statistics['total_penduduk']++;
                
                // Jenis Kelamin (kolom 3, index 2)
                if (isset($data[2])) {
                    $jk = trim($data[2]);
                    if (stripos($jk, 'LAKI') !== false) {
                        $statistics['jenis_kelamin']['Laki-laki']++;
                    } else {
                        $statistics['jenis_kelamin']['Perempuan']++;
                    }
                }
                
                // Agama (kolom 7, index 6)
                if (isset($data[6]) && trim($data[6]) !== '') {
                    $agama = trim($data[6]);
                    $statistics['agama'][$agama] = ($statistics['agama'][$agama] ?? 0) + 1;
                }
                
                // Pekerjaan (kolom 9, index 8)
                if (isset($data[8]) && trim($data[8]) !== '') {
                    $pekerjaan = trim($data[8]);
                    $statistics['pekerjaan'][$pekerjaan] = ($statistics['pekerjaan'][$pekerjaan] ?? 0) + 1;
                }
                
                // Status Perkawinan (kolom 4, index 3)
                if (isset($data[3]) && trim($data[3]) !== '') {
                    $status = trim($data[3]);
                    $statistics['status_perkawinan'][$status] = ($statistics['status_perkawinan'][$status] ?? 0) + 1;
                }
                
                // RT Distribution (kolom 13, index 12)
                if (isset($data[12]) && trim($data[12]) !== '') {
                    $rt = trim($data[12]);
                    $statistics['rt_distribution'][$rt] = ($statistics['rt_distribution'][$rt] ?? 0) + 1;
                }
                
                // RW Distribution (kolom 14, index 13)
                if (isset($data[13]) && trim($data[13]) !== '') {
                    $rw = trim($data[13]);
                    $statistics['rw_distribution'][$rw] = ($statistics['rw_distribution'][$rw] ?? 0) + 1;
                }
                
                // Usia Distribution (berdasarkan tanggal lahir kolom 6, index 5)
                if (isset($data[5]) && trim($data[5]) !== '') {
                    $tanggalLahir = trim($data[5]);
                    if (preg_match('/(\d{4})-(\d{2})-(\d{2})/', $tanggalLahir, $matches)) {
                        $tahunLahir = intval($matches[1]);
                        $usia = date('Y') - $tahunLahir;
                        
                        if ($usia <= 17) {
                            $statistics['usia_distribution']['0-17']++;
                        } elseif ($usia <= 25) {
                            $statistics['usia_distribution']['18-25']++;
                        } elseif ($usia <= 35) {
                            $statistics['usia_distribution']['26-35']++;
                        } elseif ($usia <= 45) {
                            $statistics['usia_distribution']['36-45']++;
                        } elseif ($usia <= 55) {
                            $statistics['usia_distribution']['46-55']++;
                        } elseif ($usia <= 65) {
                            $statistics['usia_distribution']['56-65']++;
                        } else {
                            $statistics['usia_distribution']['65+']++;
                        }
                    }
                }
            }
            fclose($handle);
        }
    }
    
    return $statistics;
}

$statistics = getVillageStatistics();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Desa Ngunut</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { 
            background: linear-gradient(135deg, #e0e7ef 0%, #f8f9fa 100%); 
            font-family: 'Poppins', 'Segoe UI', Arial, sans-serif; 
        }
        .hero-stats {
            background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
            color: white;
            padding: 50px 0;
            text-align: center;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.18);
        }
        .stats-card {
            background: rgba(255,255,255,0.9);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.10);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.18);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 30px;
        }
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 40px 0 rgba(31,38,135,0.18);
        }
        .stats-icon {
            font-size: 3rem;
            color: #0d6efd;
            margin-bottom: 15px;
        }
        .stats-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #0d6efd;
            margin-bottom: 10px;
        }
        .stats-label {
            font-size: 1.1rem;
            color: #6c757d;
            font-weight: 500;
        }
        .chart-container {
            position: relative;
            height: 400px;
            margin: 20px 0;
        }
        .btn-back {
            margin-top: 30px;
            font-weight: 600;
            border-radius: 30px;
            padding: 12px 32px;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px #0d6efd22;
            background: linear-gradient(90deg, #0d6efd 60%, #0099ff 100%);
            border: none;
            transition: background 0.3s, transform 0.2s;
        }
        .btn-back:hover {
            background: linear-gradient(90deg, #0099ff 60%, #0d6efd 100%);
            transform: scale(1.04);
        }
        .filter-buttons {
            margin-bottom: 20px;
        }
        .filter-btn {
            margin: 5px;
            border-radius: 20px;
            padding: 8px 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="hero-stats animate__animated animate__fadeInDown">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Statistik Desa Ngunut</h1>
            <p class="lead">Data demografis dan statistik penduduk desa</p>
        </div>
    </div>

    <div class="container my-5">
        <!-- Summary Cards -->
        <div class="row mb-5">
            <div class="col-md-3 mb-4">
                <div class="stats-card text-center p-4 animate__animated animate__fadeInUp">
                    <div class="stats-icon"><i class='bx bxs-group'></i></div>
                    <div class="stats-number"><?= number_format($statistics['total_penduduk']) ?></div>
                    <div class="stats-label">Total Penduduk</div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stats-card text-center p-4 animate__animated animate__fadeInUp animate__delay-1s">
                    <div class="stats-icon"><i class='bx bxs-home'></i></div>
                    <div class="stats-number"><?= count($statistics['rt_distribution']) ?></div>
                    <div class="stats-label">Jumlah RT</div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stats-card text-center p-4 animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="stats-icon"><i class='bx bxs-map'></i></div>
                    <div class="stats-number"><?= count($statistics['rw_distribution']) ?></div>
                    <div class="stats-label">Jumlah RW</div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stats-card text-center p-4 animate__animated animate__fadeInUp animate__delay-3s">
                    <div class="stats-icon"><i class='bx bxs-chart'></i></div>
                    <div class="stats-number"><?= count($statistics['pekerjaan']) ?></div>
                    <div class="stats-label">Jenis Pekerjaan</div>
                </div>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="filter-buttons text-center">
            <button class="btn btn-outline-primary filter-btn active" data-chart="gender">Jenis Kelamin</button>
            <button class="btn btn-outline-primary filter-btn" data-chart="religion">Agama</button>
            <button class="btn btn-outline-primary filter-btn" data-chart="occupation">Pekerjaan</button>
            <button class="btn btn-outline-primary filter-btn" data-chart="marital">Status Perkawinan</button>
            <button class="btn btn-outline-primary filter-btn" data-chart="age">Distribusi Usia</button>
            <button class="btn btn-outline-primary filter-btn" data-chart="rt">Distribusi RT</button>
        </div>

        <!-- Chart Container -->
        <div class="stats-card p-4">
            <div class="chart-container">
                <canvas id="statisticsChart"></canvas>
            </div>
        </div>

        <!-- Detail Statistics Table -->
        <div class="row mt-5">
            <div class="col-md-6 mb-4">
                <div class="stats-card p-4">
                    <h5 class="text-center mb-4"><i class='bx bxs-detail'></i> Detail Statistik</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Kategori</th>
                                    <th class="text-end">Jumlah</th>
                                    <th class="text-end">Persentase</th>
                                </tr>
                            </thead>
                            <tbody id="detailTable">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="stats-card p-4">
                    <h5 class="text-center mb-4"><i class='bx bxs-info-circle'></i> Informasi</h5>
                    <div id="chartInfo">
                        <p class="text-muted">Pilih kategori di atas untuk melihat detail statistik.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center">
            <a href="about.php" class="btn btn-back animate__animated animate__fadeInUp animate__delay-4s">
                <i class='bx bx-arrow-back'></i> Kembali ke Tentang Desa
            </a>
        </div>
    </div>

    <script>
        // Data untuk chart
        const chartData = {
            gender: {
                labels: <?= json_encode(array_keys($statistics['jenis_kelamin'])) ?>,
                data: <?= json_encode(array_values($statistics['jenis_kelamin'])) ?>,
                title: 'Distribusi Jenis Kelamin'
            },
            religion: {
                labels: <?= json_encode(array_keys($statistics['agama'])) ?>,
                data: <?= json_encode(array_values($statistics['agama'])) ?>,
                title: 'Distribusi Agama'
            },
            occupation: {
                labels: <?= json_encode(array_keys($statistics['pekerjaan'])) ?>,
                data: <?= json_encode(array_values($statistics['pekerjaan'])) ?>,
                title: 'Distribusi Pekerjaan'
            },
            marital: {
                labels: <?= json_encode(array_keys($statistics['status_perkawinan'])) ?>,
                data: <?= json_encode(array_values($statistics['status_perkawinan'])) ?>,
                title: 'Distribusi Status Perkawinan'
            },
            age: {
                labels: <?= json_encode(array_keys($statistics['usia_distribution'])) ?>,
                data: <?= json_encode(array_values($statistics['usia_distribution'])) ?>,
                title: 'Distribusi Usia'
            },
            rt: {
                labels: <?= json_encode(array_keys($statistics['rt_distribution'])) ?>,
                data: <?= json_encode(array_values($statistics['rt_distribution'])) ?>,
                title: 'Distribusi RT'
            }
        };

        // Inisialisasi chart
        const ctx = document.getElementById('statisticsChart').getContext('2d');
        let currentChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: chartData.gender.labels,
                datasets: [{
                    data: chartData.gender.data,
                    backgroundColor: [
                        '#0d6efd',
                        '#dc3545',
                        '#198754',
                        '#ffc107',
                        '#6f42c1',
                        '#fd7e14',
                        '#20c997',
                        '#e83e8c'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: chartData.gender.title,
                        font: {
                            size: 18,
                            weight: 'bold'
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Event listeners untuk filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                const chartType = this.dataset.chart;
                const data = chartData[chartType];
                
                // Update chart
                currentChart.data.labels = data.labels;
                currentChart.data.datasets[0].data = data.data;
                currentChart.options.plugins.title.text = data.title;
                currentChart.update();
                
                // Update detail table
                updateDetailTable(data);
                
                // Update info section
                updateChartInfo(chartType);
            });
        });

        // Function untuk update detail table
        function updateDetailTable(data) {
            const table = document.getElementById('detailTable');
            const total = data.data.reduce((a, b) => a + b, 0);
            
            let html = '';
            data.labels.forEach((label, index) => {
                const value = data.data[index];
                const percentage = ((value / total) * 100).toFixed(1);
                html += `
                    <tr>
                        <td><strong>${label}</strong></td>
                        <td class="text-end">${value}</td>
                        <td class="text-end">${percentage}%</td>
                    </tr>
                `;
            });
            
            // Add total row
            html += `
                <tr class="table-primary">
                    <td><strong>TOTAL</strong></td>
                    <td class="text-end"><strong>${total}</strong></td>
                    <td class="text-end"><strong>100%</strong></td>
                </tr>
            `;
            
            table.innerHTML = html;
        }

        // Function untuk update chart info
        function updateChartInfo(chartType) {
            const infoDiv = document.getElementById('chartInfo');
            const data = chartData[chartType];
            const total = data.data.reduce((a, b) => a + b, 0);
            
            let info = '';
            switch(chartType) {
                case 'gender':
                    info = `
                        <p><strong>Distribusi Jenis Kelamin:</strong></p>
                        <ul>
                            <li>Total penduduk: ${total} orang</li>
                            <li>Rasio gender: ${((data.data[0] / total) * 100).toFixed(1)}% Laki-laki, ${((data.data[1] / total) * 100).toFixed(1)}% Perempuan</li>
                        </ul>
                    `;
                    break;
                case 'religion':
                    info = `
                        <p><strong>Distribusi Agama:</strong></p>
                        <ul>
                            <li>Total penduduk: ${total} orang</li>
                            <li>Keanekaragaman agama: ${data.labels.length} jenis agama</li>
                            <li>Agama mayoritas: ${data.labels[0]} (${((data.data[0] / total) * 100).toFixed(1)}%)</li>
                        </ul>
                    `;
                    break;
                case 'occupation':
                    info = `
                        <p><strong>Distribusi Pekerjaan:</strong></p>
                        <ul>
                            <li>Total penduduk: ${total} orang</li>
                            <li>Jenis pekerjaan: ${data.labels.length} jenis</li>
                            <li>Pekerjaan utama: ${data.labels[0]} (${((data.data[0] / total) * 100).toFixed(1)}%)</li>
                        </ul>
                    `;
                    break;
                case 'marital':
                    info = `
                        <p><strong>Distribusi Status Perkawinan:</strong></p>
                        <ul>
                            <li>Total penduduk: ${total} orang</li>
                            <li>Status perkawinan: ${data.labels.length} kategori</li>
                            <li>Status terbanyak: ${data.labels[0]} (${((data.data[0] / total) * 100).toFixed(1)}%)</li>
                        </ul>
                    `;
                    break;
                case 'age':
                    info = `
                        <p><strong>Distribusi Usia:</strong></p>
                        <ul>
                            <li>Total penduduk: ${total} orang</li>
                            <li>Kelompok usia: ${data.labels.length} kategori</li>
                            <li>Kelompok terbanyak: ${data.labels[0]} (${((data.data[0] / total) * 100).toFixed(1)}%)</li>
                        </ul>
                    `;
                    break;
                case 'rt':
                    info = `
                        <p><strong>Distribusi RT:</strong></p>
                        <ul>
                            <li>Total penduduk: ${total} orang</li>
                            <li>Jumlah RT: ${data.labels.length} RT</li>
                            <li>RT terbesar: RT ${data.labels[0]} (${data.data[0]} penduduk)</li>
                        </ul>
                    `;
                    break;
            }
            infoDiv.innerHTML = info;
        }

        // Initialize dengan data gender
        updateDetailTable(chartData.gender);
        updateChartInfo('gender');
    </script>
</body>
</html> 