<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = 'Portal Aplikasi Inlis Lite';

// ============ PENGATURAN ============
// File logo Banyumas, taruh di folder: uploaded_files/aplikasi/
$logoBanyumas = 'logobanyumas1.png';

// URL aplikasi Inventaris Buku (menggunakan path absolut dari root web)
$urlInventaris = '/inlislite3/inventaris/';

// Daftar kontak / sosial media (edit kalau ada yang salah)
$kontak = [
    ['tipe' => 'web',  'teks' => 'http://dinarpus.banyumaskab.go.id',  'url' => 'http://dinarpus.banyumaskab.go.id'],
    ['tipe' => 'web',  'teks' => 'http://silibry.banyumaskab.go.id',   'url' => 'http://silibry.banyumaskab.go.id'],
    ['tipe' => 'fb',   'teks' => 'www.facebook.com/arpusda.banyumas',  'url' => 'https://www.facebook.com/arpusda.banyumas'],
    ['tipe' => 'ig',   'teks' => 'www.instagram.com/dinarpusbanyumas', 'url' => 'https://www.instagram.com/dinarpusbanyumas'],
    ['tipe' => 'mail', 'teks' => 'arpusdabanyumas@gmail.com',          'url' => 'mailto:arpusdabanyumas@gmail.com'],
];
// ====================================
?>

<style>
.portal-header { text-align: center; margin: 25px 0 30px; }

.portal-kontak { display: inline-block; text-align: left; margin-bottom: 14px; }
.portal-kontak ul { list-style: none; padding: 0; margin: 0; }
.portal-kontak li {
    display: flex; align-items: center; gap: 7px;
    font-size: 11px; line-height: 1.75;
    font-weight: bold; letter-spacing: .3px; color: #222;
}
.portal-kontak li a { color: #222; text-decoration: none; }
.portal-kontak li a:hover { text-decoration: underline; }
.portal-kontak .dot { width: 7px; height: 7px; border-radius: 50%; flex: none; }
.portal-kontak .ic {
    width: 20px; height: 20px; border-radius: 50%; flex: none;
    display: inline-flex; align-items: center; justify-content: center;
    color: #fff; font-size: 11px; font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
}
.ic-web  { background: #1e9bd7; }
.ic-fb   { background: #1877f2; }
.ic-ig   { background: #d62976; }
.ic-mail { background: #d93025; }

.portal-brand {
    display: flex; align-items: center; justify-content: center;
    gap: 18px; flex-wrap: wrap;
}
.portal-brand img { height: 105px; width: auto; }

.inlis-wordmark { text-align: left; line-height: 1; }
.inlis-baris1 { display: flex; align-items: flex-start; gap: 6px; }
.inlis-teks {
    font-family: "Arial Black", Gadget, sans-serif;
    font-size: 62px; font-weight: 900; font-style: italic;
    color: #e2000f; letter-spacing: -1px;
    text-shadow: 1px 1px 0 #7a0008;
}
.inlis-lite {
    font-family: "Arial Black", Arial, sans-serif;
    font-size: 22px; font-weight: 900; font-style: italic;
    color: #e2000f; margin-top: 4px;
}
.inlis-baris2 { display: flex; align-items: baseline; gap: 8px; margin-top: 2px; }
.inlis-sub {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 16px; font-weight: bold; font-style: italic; color: #e2000f;
}
.inlis-versi {
    font-family: "Arial Black", Arial, sans-serif;
    font-size: 30px; font-weight: 900; font-style: italic;
    color: #e2000f; text-shadow: 1px 1px 0 #7a0008;
}

.portal-dinas {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 17px; font-weight: bold; font-style: italic;
    color: #3b3b3b; letter-spacing: 1px; margin-top: 8px;
    text-shadow: 1px 1px 0 #fff;
}

@media (max-width: 600px) {
    .inlis-teks { font-size: 40px; }
    .inlis-versi { font-size: 20px; }
    .inlis-lite { font-size: 15px; }
    .inlis-sub { font-size: 11px; }
    .portal-brand img { height: 70px; }
    .portal-dinas { font-size: 13px; }
}
</style>

<div class="container">
    <div class="row">
        <div class="portal-header">

            <div class="portal-kontak">
                <ul>
                    <?php foreach ($kontak as $k): ?>
                    <li>
                        <?php if ($k['tipe'] === 'fb'): ?>
                            <span class="ic ic-fb">f</span>
                        <?php elseif ($k['tipe'] === 'ig'): ?>
                            <span class="ic ic-ig">&#9673;</span>
                        <?php elseif ($k['tipe'] === 'mail'): ?>
                            <span class="ic ic-mail">&#9993;</span>
                        <?php else: ?>
                            <span class="ic ic-web">&#8853;</span>
                        <?php endif; ?>
                        <span class="dot" style="background: <?= $k['tipe']==='fb' ? '#1877f2' : ($k['tipe']==='ig' ? '#d62976' : ($k['tipe']==='mail' ? '#d93025' : '#f0a500')) ?>"></span>
                        <a href="<?= $k['url'] ?>" target="_blank"><?= strtoupper($k['teks']) ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="portal-brand">
                <img src="<?= Url::base().'/'.Url::to('uploaded_files/aplikasi/'.$logoBanyumas) ?>" alt="Logo Kabupaten Banyumas">
                <div class="inlis-wordmark">
                    <div class="inlis-baris1">
                        <span class="inlis-teks">INLIS</span>
                        <span class="inlis-lite">LITE</span>
                    </div>
                    <div class="inlis-baris2">
                        <span class="inlis-sub">Integrated Library System</span>
                        <span class="inlis-versi">3.2</span>
                    </div>
                </div>
            </div>

            <div class="portal-dinas">
                DINAS ARSIP DAN PERPUSTAKAAN DAERAH<br>KABUPATEN BANYUMAS
            </div>

        </div>
    </div>

    <div class="row">
        <center>
            <p>
                <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('backend')?>"> Back Office</a>
                <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('bacaditempat')?>"> <?=  Yii::t('app', 'Baca ditempat') ?></a>
                <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('guestbook')?>"> <?=  Yii::t('app', 'Buku Tamu') ?></a>
                <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('keanggotaan')?>"> <?=  Yii::t('app', 'Keanggotaan Online') ?></a>
                <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('digitalcollection')?>"> <?=  Yii::t('app', 'Layanan Koleksi Digital') ?></a>
                <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('opac')?>"> <?=  Yii::t('app', 'OPAC') ?></a>
                <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('article')?>"> <?=  Yii::t('app', 'Artikel') ?></a>
                <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('pendaftaran')?>"> <?=  Yii::t('app', 'Pendaftaran Anggota') ?></a>
                <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('statistik-perkembangan-perpustakaan')?>"> <?=  Yii::t('app', 'Statistik') ?></a>
                <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('survey')?>"> <?=  Yii::t('app', 'Survey') ?></a>

                <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('pengembalianmandiri')?>"> <?=  Yii::t('app', 'Pengembalian Mandiri') ?></a>
                <a class="btn btn-primary btn3d" target="_blank" href="<?=Url::to('peminjamanmandiri')?>"> <?=  Yii::t('app', 'Peminjaman Mandiri') ?></a>
                <a class="btn btn-primary btn3d" target="_blank" href="<?= $urlInventaris ?>"> <?=  Yii::t('app', 'Inventaris Buku') ?></a>
                
            </p>
        </center>
    </div>
</div>

<div class="site-index">
    <center>
        <div class="mediumtron">
             <p>
             </p>
        </div>
    </center>
    <div class="body-content">
    </div>
</div>