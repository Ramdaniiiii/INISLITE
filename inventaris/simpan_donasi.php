<?php
require __DIR__ . '/db.php';
$data = json_decode(file_get_contents("php://input"), true);

if ($data && !empty($data['judul'])) {
    $stmt = $pdo->prepare("INSERT INTO donasi_buku (tanggal, judul, pengarang, penerbit, tempat_terbit, tahun_terbit, isbn, deskripsi_fisik, eksemplar, keterangan) VALUES (CURDATE(), ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['judul'],
        $data['pengarang'],
        $data['penerbit'],
        $data['tempat_terbit'],
        $data['tahun_terbit'],
        $data['isbn'],
        $data['deskripsi_fisik'],
        $data['eksemplar'],
        $data['keterangan']
    ]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Judul wajib diisi!']);
}