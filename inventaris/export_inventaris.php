<?php
// export_inventaris.php?format=excel   atau   ?format=pdf
require __DIR__ . '/db.php';

$rows = $pdo->query('SELECT * FROM inventaris_buku ORDER BY tanggal, id')->fetchAll(PDO::FETCH_ASSOC);

$header = ['No', 'Tanggal', 'No. Inventaris', 'ISBN', 'Judul', 'Pengarang', 'Penerbit', 'Kota Terbit',
    'Tahun Terbit', 'Deskripsi Fisik', 'No. Klasifikasi', 'Asal/Sumber', 'Jumlah', 'Keterangan'];

$data = [];
foreach ($rows as $i => $r) {
    $data[] = [
        $i + 1,
        date('d-m-Y', strtotime($r['tanggal'])),
        $r['no_inventaris'], $r['isbn'], $r['judul'], $r['pengarang'], $r['penerbit'], $r['kota_terbit'],
        $r['tahun_terbit'], $r['deskripsi_fisik'], $r['no_klasifikasi'], $r['asal_sumber'], $r['jumlah'], $r['keterangan'],
    ];
}

$format = $_GET['format'] ?? 'excel';

if ($format === 'excel') {
    require __DIR__ . '/excel_writer.php';
    unduh_excel('inventaris-buku-' . date('Y-m-d'), $header, $data, 'Inventaris Buku');
    exit;
}

// ---- format=pdf : tampilkan halaman siap-cetak, browser yang convert ke PDF ----
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Inventaris Buku</title>
<style>
    body { font-family: Arial, sans-serif; font-size: 11px; color: #1c2530; padding: 20px; }
    h1 { font-size: 16px; margin: 0 0 2px; }
    p { margin: 0 0 14px; color: #5b6472; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ccc; padding: 5px 6px; text-align: left; }
    th { background: #161d29; color: #fff; font-size: 10px; text-transform: uppercase; }
    tr:nth-child(even) td { background: #f6f7fa; }
</style>
</head>
<body>
    <h1>Data Inventaris Buku</h1>
    <p>Dinas Arsip dan Perpustakaan Daerah Kabupaten Banyumas &middot; Dicetak <?= date('d F Y, H:i') ?></p>
    <table>
        <thead><tr><?php foreach ($header as $h): ?><th><?= htmlspecialchars($h) ?></th><?php endforeach; ?></tr></thead>
        <tbody>
        <?php foreach ($data as $row): ?>
            <tr><?php foreach ($row as $val): ?><td><?= htmlspecialchars((string) $val) ?></td><?php endforeach; ?></tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <script>window.onload = () => window.print();</script>
</body>
</html>
