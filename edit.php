<?php
require 'config.php';
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}


$id = $_GET['id'];
$sql = "SELECT * FROM barang WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if(isset($_POST['submit'])){
    $nama = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];
    $gambar_lama = $row['gambar'];

    // Jika ada gambar baru diupload
    if(!empty($_FILES['gambar']['name'])){
        $gambar = $_FILES['gambar']['name'];
        $target = "uploads/" . basename($gambar);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES['gambar']['tmp_name']);
        if($check === false) {
            $uploadOk = 0;
        }
        if($uploadOk == 1){
            move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
            // Hapus gambar lama jika ada
            if($gambar_lama && file_exists("uploads/$gambar_lama")){
                unlink("uploads/$gambar_lama");
            }
        } else {
            echo "File bukan gambar";
            exit;
        }
    } else {
        $gambar = $gambar_lama;
    }

    $sql = "UPDATE barang SET nama_barang='$nama', harga='$harga', stok='$stok', deskripsi='$deskripsi', gambar='$gambar' WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if($result){
        header("Location: index.php");
    } else {
        echo "Gagal mengedit data";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Edit Barang</h2>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" value="<?= $row['nama_barang'] ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" value="<?= $row['harga'] ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" value="<?= $row['stok'] ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" required><?= $row['deskripsi'] ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <input type="file" name="gambar" class="form-control">
                        <img src="uploads/<?= $row['gambar'] ?>" width="100" class="mt-2">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
