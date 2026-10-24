<?php
require __DIR__ . '/db.php';
$id = $_GET['id'] ?? 0;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM donasi_buku WHERE id = ?");
    $stmt->execute([$id]);
}