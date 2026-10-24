<?php
// simpan_inventaris.php — dipanggil via fetch() dari index.php saat klik "Simpan"
header('Content-Type: application/json');
require __DIR__ . '/db.php';

$input = json_decode(file_get_contents('php://input'), true) ?: [];

$tanggal       = trim($input['tanggal'] ?? '');
$no_inventaris = trim($input['no_inventaris'] ?? '');
$judul         = trim($input['judul'] ?? '');
$jumlah        = (int) ($input['jumlah'] ?? 0);

if ($tanggal === '' || $no_inventaris === '' || $judul === '' || $jumlah < 1) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Tanggal, No. Inventaris, Judul, dan Jumlah wajib diisi.']);
    exit;
}

try {
    $stmt = $pdo->prepare('
        INSERT INTO inventaris_buku
            (tanggal, no_inventaris, isbn, judul, pengarang, penerbit, kota_terbit,
             tahun_terbit, deskripsi_fisik, no_klasifikasi, asal_sumber, jumlah, keterangan)
        VALUES
            (:tanggal, :no_inventaris, :isbn, :judul, :pengarang, :penerbit, :kota_terbit,
             :tahun_terbit, :deskripsi_fisik, :no_klasifikasi, :asal_sumber, :jumlah, :keterangan)
    ');
    $stmt->execute([
        'tanggal'         => $tanggal,
        'no_inventaris'   => $no_inventaris,
        'isbn'            => $input['isbn'] ?? null,
        'judul'           => $judul,
        'pengarang'       => $input['pengarang'] ?? null,
        'penerbit'        => $input['penerbit'] ?? null,
        'kota_terbit'     => $input['kota_terbit'] ?? null,
        'tahun_terbit'    => $input['tahun_terbit'] ?? null,
        'deskripsi_fisik' => $input['deskripsi_fisik'] ?? null,
        'no_klasifikasi'  => $input['no_klasifikasi'] ?? null,
        'asal_sumber'     => $input['asal_sumber'] ?? null,
        'jumlah'          => $jumlah,
        'keterangan'      => $input['keterangan'] ?? null,
    ]);

    // Siapkan nomor inventaris berikutnya untuk form selanjutnya
    $next = $pdo->query('SELECT MAX(CAST(no_inventaris AS UNSIGNED)) AS m FROM inventaris_buku')->fetch();
    $nextNoInventaris = str_pad((int) ($next['m'] ?? 0) + 1, 8, '0', STR_PAD_LEFT);

    echo json_encode(['success' => true, 'next_no_inventaris' => $nextNoInventaris]);
} catch (PDOException $e) {
    http_response_code(500);
    if ($e->getCode() === '23000') {
        echo json_encode(['success' => false, 'message' => 'No. Inventaris ini sudah dipakai. Coba muat ulang halaman.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()]);
    }
}
