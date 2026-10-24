<?php
require __DIR__ . '/db.php';
$format = $_GET['format'] ?? 'excel';

$stmt = $pdo->query("SELECT * FROM donasi_buku ORDER BY id ASC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalEksemplar = 0;
foreach ($rows as $r) {
    $totalEksemplar += (int)$r['eksemplar'];
}

if ($format === 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Berita_Acara_Serah_Terima_Donasi.xls");
    
    echo "<h3 style='text-align:center;'>DAFTAR BUKU DONASI PERPUSTAKAAN DESA PANDAK</h3>";
    echo "<br>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<thead>";
    echo "<tr style='background-color: #f2f2f2;'>";
    echo "<th style='padding: 5px; text-align: center;'>NO</th>";
    echo "<th style='padding: 5px; text-align: left;'>JUDUL</th>";
    echo "<th style='padding: 5px; text-align: left;'>PENGARANG</th>";
    echo "<th style='padding: 5px; text-align: center;'>EKSEMPLAR</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    foreach ($rows as $i => $r) {
        echo "<tr>";
        echo "<td align='center' style='padding: 4px;'>".($i + 1)."</td>";
        echo "<td style='padding: 4px;'>{$r['judul']}</td>";
        echo "<td style='padding: 4px;'>".($r['pengarang'] ?: '-')."</td>";
        echo "<td align='center' style='padding: 4px;'>{$r['eksemplar']}</td>";
        echo "</tr>";
    }
    
    echo "<tr>";
    echo "<td colspan='3' align='right' style='padding: 5px; font-weight: bold;'>JUMLAH</td>";
    echo "<td align='center' style='padding: 5px; font-weight: bold;'>{$totalEksemplar}</td>";
    echo "</tr>";
    
    echo "</tbody>";
    echo "</table>";
    
    echo "<br><br>";
    echo "<table width='100%' style='border: none;'>";
    echo "<tr>";
    echo "<td colspan='2' align='right' style='padding-bottom: 20px;'>Purwokerto, Agustus 2026</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='center' width='50%'>Yang Menyerahkan,<br><br><br><br><b>( ............................................ )</b></td>";
    echo "<td align='center' width='50%'>Yang Menerima,<br><br><br><br><b>( ............................................ )</b></td>";
    echo "</tr>";
    echo "</table>";

} else {
    echo "<h3 align='center'>DAFTAR BUKU DONASI PERPUSTAKAAN DESA PANDAK</h3>";
    echo "<table border='1' cellpadding='6' cellspacing='0' style='width:100%; border-collapse:collapse; font-size:12px;'>";
    echo "<thead><tr style='background:#f4f4f4;'><th>NO</th><th>JUDUL</th><th>PENGARANG</th><th>EKSEMPLAR</th></tr></thead><tbody>";
    
    foreach ($rows as $i => $r) {
        echo "<tr>";
        echo "<td align='center'>".($i + 1)."</td>";
        echo "<td>{$r['judul']}</td>";
        echo "<td>".($r['pengarang'] ?: '-')."</td>";
        echo "<td align='center'>{$r['eksemplar']}</td>";
        echo "</tr>";
    }
    
    echo "<tr>";
    echo "<td colspan='3' align='right' style='font-weight: bold;'>JUMLAH</td>";
    echo "<td align='center' style='font-weight: bold;'>{$totalEksemplar}</td>";
    echo "</tr>";
    
    echo "</tbody></table>";
    
    echo "<br><br><table style='width:100%; font-size:12px; border:none;'>";
    echo "<tr><td colspan='2' align='right' style='padding-bottom:15px;'>Purwokerto, Agustus 2026</td></tr>";
    echo "<tr>";
    echo "<td align='center' style='width:50%'>Yang Menyerahkan,<br><br><br><br><b>( ............................................ )</b></td>";
    echo "<td align='center' style='width:50%'>Yang Menerima,<br><br><br><br><b>( ............................................ )</b></td>";
    echo "</tr>";
    echo "</table>";
    echo "<script>window.print();</script>";
}