<?php
session_start();
require 'config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Ambil username dari session
$username = $_SESSION['user'];

// Proses pencarian data
$search = $_GET['search'] ?? '';
$sql = $search 
    ? "SELECT * FROM barang WHERE nama_barang LIKE '%$search%'" 
    : "SELECT * FROM barang";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f4f6f8;
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar {
            background: linear-gradient(to right, #667eea, #764ba2);
        }
        .navbar .navbar-brand, .navbar .nav-link {
            color: white;
        }
        .navbar .nav-link:hover {
            text-decoration: underline;
        }
        .card {
            border-radius: 16px;
            transition: all 0.3s;
        }
        .card:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .card-img-top {
            height: 180px;
            object-fit: cover;
        }
        .footer {
            background: #2d3748;
            color: white;
            padding: 20px 0;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="fas fa-boxes me-2"></i>CRUD Barang</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon bg-light"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item me-3">
                    <span class="nav-link disabled text-white"><i class="fas fa-user"></i> <?= htmlspecialchars($username) ?></span>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-white"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Konten -->
<main class="container py-5">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-box-open me-2"></i>Data Barang</h4>
        </div>
        <div class="card-body">
            <!-- Form Cari & Tambah -->
            <form method="GET" class="row g-2 mb-4">
                <div class="col-md-8">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Cari barang...">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Cari</button>
                </div>
                <div class="col-md-2">
                    <a href="tambah.php" class="btn btn-success w-100"><i class="fas fa-plus"></i> Tambah</a>
                </div>
            </form>

            <!-- Kartu Barang -->
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <div class="col">
                            <div class="card h-100">
                                <img src="<?= $row['gambar'] ? "uploads/{$row['gambar']}" : 'https://via.placeholder.com/300x180?text=No+Image' ?>" class="card-img-top" alt="Gambar Barang">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($row['nama_barang']) ?></h5>
                                    <p class="card-text">
                                        <strong>Harga:</strong> Rp <?= number_format($row['harga'], 0, ',', '.') ?><br>
                                        <strong>Stok:</strong> <?= $row['stok'] ?><br>
                                        <small><?= htmlspecialchars($row['deskripsi']) ?></small>
                                    </p>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus barang ini?')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-warning text-center">Data tidak ditemukan.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="footer text-center">
    <div class="container">
        <p class="mb-0">© 2025 CRUD Barang. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
