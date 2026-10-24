<?php
require __DIR__ . '/db.php';
$stmt = $pdo->query("SELECT * FROM donasi_buku ORDER BY id DESC");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));