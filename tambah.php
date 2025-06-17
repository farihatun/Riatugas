<?php
require 'config.php';
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}


$nama = $harga = $stok = $deskripsi = '';
$errors = [];

if(isset($_POST['submit'])){
    $nama = trim($_POST['nama_barang']);
    $harga = trim($_POST['harga']);
    $stok = trim($_POST['stok']);
    $deskripsi = trim($_POST['deskripsi']);
    $gambar = $_FILES['gambar']['name'];
    $target = "uploads/" . basename($gambar);

    // Validasi input
    if(empty($nama)) $errors[] = "Nama barang harus diisi";
    if(empty($harga) || !is_numeric($harga)) $errors[] = "Harga harus diisi dan berupa angka";
    if(empty($stok) || !is_numeric($stok)) $errors[] = "Stok harus diisi dan berupa angka";
    if(empty($deskripsi)) $errors[] = "Deskripsi harus diisi";
    if(empty($gambar)) $errors[] = "Gambar harus diupload";

    // Validasi file upload jika tidak ada error
    if(empty($errors)){
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES['gambar']['tmp_name']);
        if($check === false) {
            $errors[] = "File bukan gambar";
            $uploadOk = 0;
        }
        if($uploadOk == 1){
            if(move_uploaded_file($_FILES['gambar']['tmp_name'], $target)){
                $sql = "INSERT INTO barang (nama_barang, harga, stok, deskripsi, gambar) VALUES ('$nama', '$harga', '$stok', '$deskripsi', '$gambar')";
                $result = mysqli_query($conn, $sql);
                if($result){
                    header("Location: index.php");
                    exit();
                } else {
                    $errors[] = "Gagal menambah data ke database";
                }
            } else {
                $errors[] = "Gagal upload gambar";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0;
        }
        .btn-primary {
            background: #667eea;
            border: none;
        }
        .btn-primary:hover {
            background: #5a6fcc;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .card-body {
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header">
                <h2 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Tambah Barang</h2>
            </div>
            <div class="card-body">
                <?php if(!empty($errors)): ?>
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Terjadi kesalahan!</strong> Mohon perbaiki input berikut:
                        <ul class="mb-0 mt-2">
                            <?php foreach($errors as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="form-label"><i class="fas fa-box me-2"></i>Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" value="<?= htmlspecialchars($nama) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label"><i class="fas fa-tag me-2"></i>Harga</label>
                        <input type="number" name="harga" class="form-control" value="<?= htmlspecialchars($harga) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label"><i class="fas fa-boxes me-2"></i>Stok</label>
                        <input type="number" name="stok" class="form-control" value="<?= htmlspecialchars($stok) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label"><i class="fas fa-align-left me-2"></i>Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" required><?= htmlspecialchars($deskripsi) ?></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label"><i class="fas fa-image me-2"></i>Gambar</label>
                        <input type="file" name="gambar" class="form-control" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
