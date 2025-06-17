<?php
require 'config.php';
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}


$id = $_GET['id'];
$sql = "SELECT gambar FROM barang WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Hapus gambar dari folder
if($row['gambar'] && file_exists("uploads/".$row['gambar'])){
    unlink("uploads/".$row['gambar']);
}

$sql = "DELETE FROM barang WHERE id = $id";
$result = mysqli_query($conn, $sql);

if($result){
    header("Location: index.php");
} else {
    echo "Gagal menghapus data";
}
?>
