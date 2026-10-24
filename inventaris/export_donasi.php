<?php
require __DIR__ . '/db.php';
$format = $_GET['format'] ?? 'excel';

$stmt = $pdo->query("SELECT * FROM donasi_buku ORDER BY id ASC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($format === 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Daftar_Buku_Donasi_Lengkap.xls");
    
    echo "<h3 style='text-align:center;'>DAFTAR BANTUAN BUKU DONASI UNTUK DESA/SEKOLAH/INSTANSI</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<thead>";
    echo "<tr style='background-color: #f2f2f2;'>";
    echo "<th>KETERANGAN</th><th>TANGGAL</th><th>NO</th><th>JUDUL</th><th>PENGARANG</th><th>PENERBIT</th><th>TEMPAT TERBIT</th><th>TAHUN TERBIT</th><th>ISBN</th><th>DESKRIPSI FISIK</th><th>EKSEMPLAR</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    foreach ($rows as $i => $r) {
        $ketCol = '';
        $tglCol = '';
        if($i === 0) $ketCol = 'Tanggal Permohonan : 17 Januari 2026';
        else if($i === 1) $ketCol = 'Tanggal Penerima : 4 Februari 2026';
        else if($i === 2) $ketCol = 'Petugas Yang Menyerahkan : Virda';
        else if($i === 3) $ketCol = 'Pihak Yang Menerima : Ibnu';
        else if($i === 4) $ketCol = 'Jumlah Buku : '.count($rows);
        else if($i === 5) $ketCol = 'Desa Purwodadi Kecamatan Tambak (Mahasiswa KKN)';

        if($i === 0) $tglCol = '3 Februari 2026';

        echo "<tr>";
        echo "<td><b>{$ketCol}</b></td>";
        echo "<td>{$tglCol}</td>";
        echo "<td align='center'>".($i + 1)."</td>";
        echo "<td>{$r['judul']}</td>";
        echo "<td>{$r['pengarang']}</td>";
        echo "<td>{$r['penerbit']}</td>";
        echo "<td>{$r['tempat_terbit']}</td>";
        echo "<td align='center'>{$r['tahun_terbit']}</td>";
        echo "<td>{$r['isbn']}</td>";
        echo "<td>{$r['deskripsi_fisik']}</td>";
        echo "<td align='center'>{$r['eksemplar']}</td>";
        echo "</tr>";
    }
    
    echo "</tbody>";
    echo "</table>";
} else {
    // Mode PDF: Jika ingin langsung terunduh sebagai dokumen HTML/PDF atau tetap cetak bersih
    header("Content-Type: application/pdf");
    header("Content-Disposition: inline; filename=Daftar_Buku_Donasi.pdf");
    
    // Atau jika ingin tetap menggunakan jendela cetak browser yang bersih namun otomatis terpanggil:
    echo "<h3 align='center'>DAFTAR BANTUAN BUKU DONASI</h3>";
    echo "<table border='1' cellpadding='5' cellspacing='0' style='width:100%; border-collapse:collapse; font-size:11px;'>";
    echo "<thead><tr style='background:#f4f4f4;'><th>Keterangan</th><th>Tanggal</th><th>No</th><th>Judul</th><th>Pengarang</th><th>Penerbit</th><th>Tahun</th><th>ISBN</th><th>Eks</th></tr></thead><tbody>";
    
    foreach ($rows as $i => $r) {
        echo "<tr>";
        echo "<td>-</td><td>-</td>";
        echo "<td align='center'>".($i + 1)."</td>";
        echo "<td>{$r['judul']}</td>";
        echo "<td>{$r['pengarang']}</td>";
        echo "<td>{$r['penerbit']}</td>";
        echo "<td align='center'>{$r['tahun_terbit']}</td>";
        echo "<td>{$r['isbn']}</td>";
        echo "<td align='center'>{$r['eksemplar']}</td>";
        echo "</tr>";
    }
    
    echo "</tbody></table>";
    echo "<script>window.print();</script>";
}