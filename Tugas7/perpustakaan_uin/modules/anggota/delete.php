<?php
require_once '../../config/db.php';

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id === 0) { header("Location: index.php"); exit; }

// ambil data dulu untuk hapus fotonya
$stmt = $conn->prepare("SELECT foto, nama FROM anggota WHERE id_anggota = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$anggota = $stmt->get_result()->fetch_assoc();
if (!$anggota) { header("Location: index.php"); exit; }

// hapus foto jika ada 
if ($anggota['foto'] && file_exists("uploads/" . $anggota['foto'])) {
    unlink("uploads/" . $anggota['foto']);
}

// delete dari database 
$stmt = $conn->prepare("DELETE FROM anggota WHERE id_anggota = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php?msg=deleted");
exit;