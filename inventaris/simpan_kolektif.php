<?php
// simpan_kolektif.php — menerima 1 sesi peminjaman berisi banyak baris buku sekaligus
header('Content-Type: application/json');
require __DIR__ . '/db.php';

$input = json_decode(file_get_contents('php://input'), true) ?: [];

$tingkat   = trim($input['tingkat_pendidikan'] ?? '');
$sekolah   = trim($input['nama_sekolah'] ?? '');
$pj        = trim($input['penanggung_jawab'] ?? '');
$items     = $input['items'] ?? [];

if ($tingkat === '' || $sekolah === '' || !is_array($items) || count($items) === 0) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Tingkat pendidikan, nama sekolah, dan minimal 1 buku wajib diisi.']);
    exit;
}

try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('
        INSERT INTO peminjaman_kolektif
            (tanggal_pinjam, tingkat_pendidikan, nama_sekolah, penanggung_jawab, no_inventaris, judul_buku, jumlah)
        VALUES
            (:tanggal_pinjam, :tingkat_pendidikan, :nama_sekolah, :penanggung_jawab, :no_inventaris, :judul_buku, :jumlah)
    ');

    foreach ($items as $item) {
        $stmt->execute([
            'tanggal_pinjam'     => $item['tanggal'] ?? date('Y-m-d'),
            'tingkat_pendidikan' => $tingkat,
            'nama_sekolah'       => $sekolah,
            'penanggung_jawab'   => $pj,
            'no_inventaris'      => $item['no_inventaris'] ?? null,
            'judul_buku'         => $item['judul_buku'] ?? null,
            'jumlah'             => (int) ($item['jumlah'] ?? 1),
        ]);
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'jumlah_tersimpan' => count($items)]);
} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()]);
}
