<?php
require __DIR__ . '/db.php';

$today = date('Y-m-d');
$next = $pdo->query('SELECT MAX(CAST(no_inventaris AS UNSIGNED)) AS m FROM inventaris_buku')->fetch();
$nextNoInventaris = str_pad((int) ($next['m'] ?? 0) + 1, 8, '0', STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris & Donasi Buku - Arpusda Banyumas</title>
    <!-- Menggunakan Bootstrap 3 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body { 
            margin: 0; 
            background-color: #ecf0f5; 
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; 
            font-size: 13px; 
        }
        
        /* HEADER STYLES */
        .top-header {
            background-color: #316593; 
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: stretch;
            height: 75px;
            padding: 0 15px;
            position: relative;
            z-index: 100;
        }
        .header-left { display: flex; align-items: center; }
        .menu-toggle { font-size: 20px; cursor: pointer; margin-right: 15px; }
        
        /* STYLE LOGO BULAT */
        .logo-circle {
            width: 48px; height: 48px;
            border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            overflow: hidden; margin-right: 15px;
            background: transparent;
        }
        .logo-circle img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            border-radius: 50%;
        }
        
        .header-titles { display: flex; flex-direction: column; justify-content: center; }
        .title-main { font-size: 16px; font-weight: bold; margin: 0 0 2px 0; display: flex; align-items: center; }
        .title-sub { font-size: 12px; font-weight: bold; margin: 0; }
        .title-address { font-size: 11px; margin: 2px 0 0 0; font-weight: normal; color: #e0e0e0; }
        
        .header-right { display: flex; flex-direction: column; justify-content: space-between; align-items: flex-end; padding: 10px 0; }
        .datetime-display { font-size: 12px; }
        .user-profile { display: flex; align-items: center; font-size: 12px; }
        .user-icon {
            width: 25px; height: 25px;
            background-color: #fff; border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            color: #316593; font-weight: bold; margin-right: 8px;
        }

        /* LAYOUT & SIDEBAR STYLES (Disesuaikan agar pas & font sama) */
        .wrapper { display: flex; min-height: calc(100vh - 75px); }
        .sidebar { 
            width: 210px; 
            background-color: #222d32; 
            color: #b8c7ce; 
            padding-top: 10px; 
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        .sidebar.hidden { margin-left: -210px; }
        .sidebar a { 
            color: #b8c7ce; 
            display: block; 
            padding: 12px 15px; 
            text-decoration: none; 
            border-left: 3px solid transparent; 
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 12px;
        }
        .sidebar a i { margin-right: 8px; font-size: 12px; }
        .sidebar a:hover, .sidebar a.active { background-color: #1e282c; color: #fff; border-left-color: #3c8dbc; }
        
        /* MAIN CONTENT STYLES */
        .main-content { flex: 1; padding: 15px 20px; background-color: #fff; }
        
        .page-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            border-bottom: 2px solid #3c8dbc; 
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .page-header-custom h3 { margin: 0; font-size: 22px; font-weight: normal; color: #333; }
        .breadcrumb-custom { font-size: 12px; color: #777; margin: 0; }
        .breadcrumb-custom a { color: #444; text-decoration: none; }
        
        /* BUTTONS */
        .btn-action-group { margin-bottom: 20px; }
        .btn-inlis-simpan { background-color: #00a65a; color: white; border: none; }
        .btn-inlis-simpan:hover { background-color: #008d4c; color: white; }
        .btn-inlis-opsi { background-color: #3c8dbc; color: white; border: none; }
        .btn-inlis-opsi:hover { background-color: #367fa9; color: white; }
        .btn-inlis-selesai { background-color: #f39c12; color: white; border: none; }
        .btn-inlis-selesai:hover { background-color: #e08e0b; color: white; }
        
        /* FORM STYLES */
        .panel-inlis { border: 1px solid #ddd; box-shadow: none; margin-bottom: 20px; }
        .panel-inlis > .panel-heading { 
            background-color: #f9fafc; 
            color: #3c8dbc; 
            font-size: 14px; 
            border-bottom: 1px solid #ddd;
            padding: 10px 15px;
            font-weight: bold;
        }
        .form-horizontal .control-label { text-align: left; font-weight: bold; font-size: 12px; padding-top: 7px; }
        
        /* Table Custom */
        .table-keranjang th, .table-donasi th { background-color: #f4f4f4; font-size: 11px; vertical-align: middle !important; text-align: center; }
        .table-keranjang td, .table-donasi td { font-size: 11px; vertical-align: middle !important; }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="top-header">
        <div class="header-left">
            <div class="menu-toggle" id="menuToggleBtn">☰</div>
            <div class="logo-circle">
                <img src="../uploaded_files/aplikasi/logobanyumas3.png" alt="Logo Banyumas">
            </div>
            <div class="header-titles">
                <div class="title-main">INTEGRATED LIBRARY SYSTEM 📖</div>
                <div class="title-sub">Dinas Arsip dan Perpustakaan Daerah Kabupaten Banyumas</div>
                <div class="title-address">Jl. Jend Gatot Subroto No.85 Purwokerto 53116, Telp. (0281) 636115, 635967(Layanan).</div>
            </div>
        </div>
        <div class="header-right">
            <div class="datetime-display" id="clock">Memuat waktu...</div>
            <div class="user-profile">
                <div class="user-icon">👤</div>
                <span>inlislite</span>
            </div>
        </div>
    </header>

    <!-- KONTEN BAWAH -->
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <a href="#" class="active" id="nav-inventaris" onclick="showTab('inventaris')">
                <i class="glyphicon glyphicon-edit"></i> Inventaris Buku
            </a>
            <a href="#" id="nav-peminjaman" onclick="showTab('peminjaman')">
                <i class="glyphicon glyphicon-share"></i> Peminjaman Kolektif
            </a>
            <a href="#" id="nav-donasi" onclick="showTab('donasi')">
                <i class="glyphicon glyphicon-book"></i> Daftar Buku Donasi
            </a>
            <a href="#" id="nav-serah" onclick="showTab('serah')">
                <i class="glyphicon glyphicon-file"></i> Berita Acara Serah Terima
            </a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            
            <!-- ==========================================
                 MODUL 1: INVENTARIS BUKU 
                 ========================================== -->
            <div id="modul-inventaris">
                <div class="page-header-custom">
                    <h3>Entri Inventaris Buku</h3>
                    <div class="breadcrumb-custom">
                        <i class="glyphicon glyphicon-home"></i> <a href="#">Dashboard</a> > <a href="#">Inventaris</a> > Entri Baru
                    </div>
                </div>

                <div class="btn-action-group">
                    <button class="btn btn-inlis-simpan btn-sm" onclick="simpanInventaris()">Simpan</button>
                    <button class="btn btn-inlis-opsi btn-sm" onclick="window.location='export_inventaris.php?format=excel'">Export Excel</button>
                    <button class="btn btn-inlis-opsi btn-sm" onclick="window.location='export_inventaris.php?format=pdf'">Export PDF</button>
                    <button class="btn btn-inlis-selesai btn-sm" onclick="selesaiInventaris()">Selesai</button>
                </div>
                <div id="inventaris-alert" style="display:none;" class="alert" role="alert"></div>

                <div class="panel panel-inlis">
                    <div class="panel-heading">Data Inventaris</div>
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tanggal Masuk</label>
                                        <div class="col-sm-8"><input type="date" class="form-control input-sm" id="input-tanggal-inventaris" value="<?= $today ?>" readonly></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">No. Inventaris</label>
                                        <div class="col-sm-8"><input type="text" class="form-control input-sm" id="input-no-inventaris" value="<?= htmlspecialchars($nextNoInventaris) ?>" readonly></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">ISBN</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                                                <input type="text" class="form-control input-sm" id="input-isbn" placeholder="Scan barcode ISBN..." autofocus>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Judul Buku</label>
                                        <div class="col-sm-8"><input type="text" class="form-control input-sm" id="input-judul"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Pengarang</label>
                                        <div class="col-sm-8"><input type="text" class="form-control input-sm" id="input-pengarang"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Penerbit & Kota</label>
                                        <div class="col-sm-4"><input type="text" class="form-control input-sm" id="input-penerbit" placeholder="Penerbit"></div>
                                        <div class="col-sm-4"><input type="text" class="form-control input-sm" id="input-kota" placeholder="Kota Terbit"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tahun Terbit</label>
                                        <div class="col-sm-8"><input type="number" class="form-control input-sm" id="input-tahun" placeholder="YYYY"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Deskripsi Fisik</label>
                                        <div class="col-sm-8"><input type="text" class="form-control input-sm" id="input-deskripsi" placeholder="viii, 200 hlm.; 21 cm"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">No. Klasifikasi</label>
                                        <div class="col-sm-8"><input type="text" class="form-control input-sm" id="input-klasifikasi"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Asal / Sumber</label>
                                        <div class="col-sm-8">
                                            <select class="form-control input-sm" id="input-asal">
                                                <option value="">- Pilih Sumber -</option>
                                                <option value="APBD">APBD</option>
                                                <option value="Hibah">Hibah</option>
                                                <option value="Hadiah">Hadiah</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Jumlah Eksemplar</label>
                                        <div class="col-sm-8"><input type="number" class="form-control input-sm" id="input-jumlah" min="1" value="1"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Keterangan</label>
                                        <div class="col-sm-8"><textarea class="form-control input-sm" id="input-keterangan" rows="3"></textarea></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 MODUL 2: PEMINJAMAN KOLEKTIF 
                 ========================================== -->
            <div id="modul-peminjaman" style="display: none;">
                <div class="page-header-custom">
                    <h3>Peminjaman Kolektif</h3>
                    <div class="breadcrumb-custom">
                        <i class="glyphicon glyphicon-home"></i> <a href="#">Dashboard</a> > Peminjaman
                    </div>
                </div>

                <div class="panel panel-inlis" id="panel-step-1">
                    <div class="panel-heading">1. Data Peminjam (Satu kali input per sesi)</div>
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tingkat Pendidikan</label>
                                        <div class="col-sm-8">
                                            <select class="form-control input-sm" id="tingkat-pendidikan">
                                                <option value="">- Pilih Tingkat -</option>
                                                <option value="TK">TK</option>
                                                <option value="SD">SD</option>
                                                <option value="SMP">SMP</option>
                                                <option value="SMA">SMA/SMK</option>
                                                <option value="LAINNYA">Lainnya / Umum</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Nama Sekolah/Instansi</label>
                                        <div class="col-sm-8"><input type="text" class="form-control input-sm" id="nama-sekolah" placeholder="Misal: SDN 1 Purwokerto"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Penanggung Jawab</label>
                                        <div class="col-sm-8"><input type="text" class="form-control input-sm" id="penanggung-jawab" placeholder="Nama Guru / Petugas"></div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <button type="button" class="btn btn-inlis-opsi" id="btn-lanjut" onclick="mulaiSesiPeminjaman()">
                                Mulai Input Buku <i class="glyphicon glyphicon-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div id="panel-step-2" style="display: none;">
                    <div class="panel panel-inlis">
                        <div class="panel-heading">2. Entri Buku yang Dipinjam</div>
                        <div class="panel-body">
                            <form class="form-inline" style="margin-bottom: 20px;">
                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="display: block; font-size: 12px; margin-bottom: 5px;">Tanggal Pinjam</label>
                                    <input type="date" class="form-control input-sm" id="peminjaman-tanggal" readonly>
                                </div>
                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="display: block; font-size: 12px; margin-bottom: 5px;">No. Inventaris</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                                        <input type="text" class="form-control input-sm" id="no-inventaris-scan" placeholder="Scan barcode..." style="width: 140px;">
                                    </div>
                                </div>
                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="display: block; font-size: 12px; margin-bottom: 5px;">Judul Buku</label>
                                    <input type="text" class="form-control input-sm" id="judul-buku" placeholder="Otomatis / Input" style="width: 200px;">
                                </div>
                                <div class="form-group" style="margin-right: 10px;">
                                    <label style="display: block; font-size: 12px; margin-bottom: 5px;">Jumlah</label>
                                    <input type="number" class="form-control input-sm" id="jumlah-buku" value="1" min="1" style="width: 70px;">
                                </div>
                                <div class="form-group" style="vertical-align: bottom;">
                                    <label style="display: block; margin-bottom: 5px;">&nbsp;</label>
                                    <button type="button" class="btn btn-inlis-opsi btn-sm" onclick="tambahKeTabel()"><i class="glyphicon glyphicon-plus"></i> Tambah</button>
                                </div>
                            </form>

                            <table class="table table-bordered table-keranjang" id="tabel-buku">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="15%">No. Inventaris</th>
                                        <th width="40%">Judul Buku</th>
                                        <th width="10%">Jumlah</th>
                                        <th width="20%">Tanggal Pinjam</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="baris-kosong"><td colspan="6" class="text-center text-muted">Belum ada buku yang di-scan.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="btn-action-group pull-right">
                        <button class="btn btn-default btn-sm" onclick="batalSesi()">Batal</button>
                        <button class="btn btn-inlis-opsi btn-sm" onclick="window.location='export_kolektif.php?format=pdf'">Export PDF</button>
                        <button class="btn btn-inlis-simpan btn-sm" onclick="simpanKeExcel()">Simpan & Export Excel</button>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 MODUL 3: DAFTAR BUKU DONASI 
                 ========================================== -->
            <div id="modul-donasi" style="display: none;">
                <div class="page-header-custom">
                    <h3>Daftar Bantuan Buku Donasi</h3>
                </div>

                <div class="btn-action-group">
                    <button class="btn btn-inlis-opsi btn-sm" onclick="window.location='export_donasi.php?format=excel'">Export Excel</button>
                    <button class="btn btn-inlis-opsi btn-sm" onclick="window.location='export_donasi.php?format=pdf'">Export PDF</button>
                </div>

                <!-- Sesi 1: Metadata Bantuan Donasi -->
                <div class="panel panel-inlis" id="panel-meta-donasi">
                    <div class="panel-heading">1. Pengaturan Keterangan & Metadata Bantuan Donasi (Satu kali input per sesi)</div>
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label">Tanggal Permohonan</label>
                                        <div class="col-sm-7"><input type="text" class="form-control input-sm" id="meta-tgl-mohon" placeholder=""></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label">Tanggal Penerima</label>
                                        <div class="col-sm-7"><input type="text" class="form-control input-sm" id="meta-tgl-terima" placeholder=""></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label">Petugas Menyerahkan</label>
                                        <div class="col-sm-7"><input type="text" class="form-control input-sm" id="meta-petugas" placeholder="Nama Petugas"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label">Pihak Menerima</label>
                                        <div class="col-sm-7"><input type="text" class="form-control input-sm" id="meta-penerima" placeholder="Nama Penerima"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label">Nama Desa / Instansi / KKN</label>
                                        <div class="col-sm-7"><textarea class="form-control input-sm" id="meta-instansi" rows="2" placeholder=""></textarea></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-5 col-sm-7">
                                            <button type="button" class="btn btn-success btn-sm" onclick="mulaiSesiDonasi()">Mulai Input Buku Donasi</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sesi 2: Form Input Buku Donasi Baru -->
                <div class="panel panel-inlis" id="panel-form-donasi" style="display: none;">
                    <div class="panel-heading">2. Tambah Buku Donasi Baru</div>
                    <div class="panel-body">
                        <form id="form-donasi" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Judul Buku</label>
                                        <div class="col-sm-8"><input type="text" class="form-control input-sm" id="d-judul" required></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Pengarang</label>
                                        <div class="col-sm-8"><input type="text" class="form-control input-sm" id="d-pengarang"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Penerbit</label>
                                        <div class="col-sm-8"><input type="text" class="form-control input-sm" id="d-penerbit"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tempat Terbit</label>
                                        <div class="col-sm-8"><input type="text" class="form-control input-sm" id="d-tempat" placeholder="Jakarta"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Tahun Terbit</label>
                                        <div class="col-sm-8"><input type="text" class="form-control input-sm" id="d-tahun" value="2026"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">ISBN</label>
                                        <div class="col-sm-8"><input type="text" class="form-control input-sm" id="d-isbn"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Deskripsi Fisik</label>
                                        <div class="col-sm-8"><input type="text" class="form-control input-sm" id="d-deskripsi" value="250 hlm.; 21 cm"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Eksemplar</label>
                                        <div class="col-sm-8"><input type="number" class="form-control input-sm" id="d-eksemplar" value="1" min="1"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Keterangan Buku</label>
                                        <div class="col-sm-8"><input type="text" class="form-control input-sm" id="d-keterangan" value="Donasi Masyarakat"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-4 col-sm-8">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="simpanDonasi()">Simpan Buku Donasi</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="panel panel-inlis">
                    <div class="panel-heading">Tabel Daftar Buku Donasi</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <h4 class="text-center" style="font-weight: bold;">DAFTAR BANTUAN BUKU DONASI UNTUK DESA/SEKOLAH/INSTANSI</h4>
                            <table class="table table-bordered table-striped table-donasi" id="tabel-donasi-data" style="margin-top: 15px;">
                                <thead>
                                    <tr>
                                        <th width="15%">KETERANGAN</th>
                                        <th width="10%">TANGGAL</th>
                                        <th width="5%">NO</th>
                                        <th width="18%">JUDUL</th>
                                        <th width="12%">PENGARANG</th>
                                        <th width="10%">PENERBIT</th>
                                        <th width="8%">TEMPAT</th>
                                        <th width="6%">TAHUN</th>
                                        <th width="10%">ISBN</th>
                                        <th width="10%">DESKRIPSI</th>
                                        <th width="5%">EKS</th>
                                        <th width="8%">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 MODUL 4: BERITA ACARA SERAH TERIMA 
                 ========================================== -->
            <div id="modul-serah" style="display: none;">
                <div class="page-header-custom">
                    <h3>Berita Acara / Lembar Serah Terima Bantuan Buku</h3>
                </div>

                <div class="btn-action-group">
                    <button class="btn btn-inlis-opsi btn-sm" onclick="window.location='export_serahterima.php?format=excel'">Export Excel</button>
                    <button class="btn btn-inlis-opsi btn-sm" onclick="window.location='export_serahterima.php?format=pdf'">Export PDF</button>
                </div>

                <div class="panel panel-inlis">
                    <div class="panel-heading">Pengaturan Judul & Tanggal Surat Serah Terima</div>
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label">Nama Perpustakaan / Desa</label>
                                        <div class="col-sm-7"><input type="text" class="form-control input-sm" id="st-perpus" value=""></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label">Kota / Tempat Surat</label>
                                        <div class="col-sm-7"><input type="text" class="form-control input-sm" id="st-kota" value=""></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-5 control-label">Tanggal / Bulan / Tahun</label>
                                        <div class="col-sm-7"><input type="text" class="form-control input-sm" id="st-tgl-surat" value=""></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-5 col-sm-7">
                                            <button type="button" class="btn btn-success btn-sm" onclick="loadPreviewSerah()">Perbarui Pratinjau</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="panel panel-inlis">
                    <div class="panel-heading">Pratinjau Serah Terima (No, Judul, Pengarang, Eksemplar)</div>
                    <div class="panel-body" style="background: #fafafa;">
                        <div style="background: #fff; padding: 25px; border: 1px solid #ccc; max-width: 850px; margin: 0 auto;">
                            <h4 class="text-center" style="font-weight: bold; margin-bottom: 20px;" id="lbl-judul-header">DAFTAR BUKU DONASI</h4>
                            
                            <table class="table table-bordered table-donasi" style="margin-top: 15px;">
                                <thead>
                                    <tr>
                                        <th width="10%">NO</th>
                                        <th width="50%">JUDUL</th>
                                        <th width="30%">PENGARANG</th>
                                        <th width="10%">EKSEMPLAR</th>
                                    </tr>
                                </thead>
                                <tbody id="preview-serah-table"></tbody>
                            </table>

                            <div class="row" style="margin-top: 40px;">
                                <div class="col-xs-6 text-center">
                                    <p>Yang Menyerahkan,</p>
                                    <br><br><br>
                                    <p><b>( ............................................ )</b></p>
                                </div>
                                <div class="col-xs-6 text-center">
                                    <p><span id="lbl-kota">...</span>, <span id="lbl-tgl-surat">...</span></p>
                                    <p>Yang Menerima,</p>
                                    <br><br><br>
                                    <p><b>( ............................................ )</b></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        document.getElementById('menuToggleBtn').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('hidden');
        });

        function updateClock() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute:'2-digit', second:'2-digit' };
            document.getElementById('clock').innerText = now.toLocaleDateString('id-ID', options).replace(/\./g, ':');
        }
        setInterval(updateClock, 1000);
        updateClock();

        function showTab(tabName) {
            ['inventaris', 'peminjaman', 'donasi', 'serah'].forEach(t => {
                document.getElementById('modul-' + t).style.display = (t === tabName) ? 'block' : 'none';
                document.getElementById('nav-' + t).classList.toggle('active', t === tabName);
            });
            localStorage.setItem('activeTab', tabName);
            if (tabName === 'serah') loadPreviewSerah();
        }

        window.onload = function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('peminjaman-tanggal').value = today;
            
            let savedTab = localStorage.getItem('activeTab');
            if (savedTab && ['inventaris', 'peminjaman', 'donasi', 'serah'].includes(savedTab)) {
                showTab(savedTab);
            } else {
                showTab('inventaris');
            }

            loadDataDonasi();
        };

        function tampilkanAlert(msg, sukses) {
            const box = document.getElementById('inventaris-alert');
            box.textContent = msg;
            box.className = 'alert ' + (sukses ? 'alert-success' : 'alert-danger');
            box.style.display = 'block';
            setTimeout(() => { box.style.display = 'none'; }, 3500);
        }

        // ================= INVENTARIS =================
        function simpanInventaris() {
            const data = {
                tanggal: document.getElementById('input-tanggal-inventaris').value,
                no_inventaris: document.getElementById('input-no-inventaris').value,
                isbn: document.getElementById('input-isbn').value,
                judul: document.getElementById('input-judul').value,
                pengarang: document.getElementById('input-pengarang').value,
                penerbit: document.getElementById('input-penerbit').value,
                kota_terbit: document.getElementById('input-kota').value,
                tahun_terbit: document.getElementById('input-tahun').value,
                deskripsi_fisik: document.getElementById('input-deskripsi').value,
                no_klasifikasi: document.getElementById('input-klasifikasi').value,
                asal_sumber: document.getElementById('input-asal').value,
                jumlah: document.getElementById('input-jumlah').value,
                keterangan: document.getElementById('input-keterangan').value,
            };
            if (!data.judul) { tampilkanAlert('Judul Buku wajib diisi.', false); return; }

            fetch('simpan_inventaris.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(hasil => {
                if (hasil.success) {
                    tampilkanAlert('Data berhasil disimpan. No. Inventaris berikutnya: ' + hasil.next_no_inventaris, true);
                    ['input-isbn','input-judul','input-pengarang','input-penerbit','input-kota','input-tahun',
                     'input-deskripsi','input-klasifikasi','input-keterangan'].forEach(id => document.getElementById(id).value = '');
                    document.getElementById('input-asal').value = '';
                    document.getElementById('input-jumlah').value = 1;
                    document.getElementById('input-no-inventaris').value = hasil.next_no_inventaris;
                    document.getElementById('input-isbn').focus();
                } else {
                    tampilkanAlert(hasil.message || 'Gagal menyimpan data.', false);
                }
            });
        }

        function selesaiInventaris() {
            if (confirm('Kembali ke halaman portal InlisLite?')) { window.location = '../index.php'; }
        }

        // ================= PEMINJAMAN KOLEKTIF =================
        function mulaiSesiPeminjaman() {
            var tingkat = document.getElementById('tingkat-pendidikan').value;
            var pj = document.getElementById('penanggung-jawab').value;
            if (tingkat === "" || pj === "") { alert("Pilih Tingkat Pendidikan dan isi Penanggung Jawab!"); return; }
            document.getElementById('tingkat-pendidikan').disabled = true;
            document.getElementById('nama-sekolah').disabled = true;
            document.getElementById('penanggung-jawab').disabled = true;
            document.getElementById('btn-lanjut').style.display = 'none';
            document.getElementById('panel-step-2').style.display = 'block';
            document.getElementById('no-inventaris-scan').focus();
        }

        let rowCount = 0;
        let daftarBukuKolektif = [];
        function tambahKeTabel() {
            var noInv = document.getElementById('no-inventaris-scan').value;
            var judulBuku = document.getElementById('judul-buku').value;
            var jumlah = document.getElementById('jumlah-buku').value;
            var tgl = document.getElementById('peminjaman-tanggal').value;
            if(noInv === "") { alert("Masukkan No Inventaris!"); return; }

            var barisKosong = document.getElementById('baris-kosong');
            if(barisKosong) barisKosong.remove();

            rowCount++;
            const rowId = 'row-' + rowCount;
            daftarBukuKolektif.push({ rowId, tanggal: tgl, no_inventaris: noInv, judul_buku: judulBuku, jumlah: jumlah });

            var tbody = document.querySelector('#tabel-buku tbody');
            var tr = document.createElement('tr');
            tr.id = rowId;
            tr.innerHTML = `<td>${rowCount}</td><td>${noInv}</td><td>${judulBuku || '-'}</td><td>${jumlah}</td><td>${tgl}</td><td><button class="btn btn-danger btn-xs" onclick="hapusBaris('${rowId}', this)">Hapus</button></td>`;
            tbody.appendChild(tr);
            document.getElementById('no-inventaris-scan').value = "";
            document.getElementById('judul-buku').value = "";
            document.getElementById('no-inventaris-scan').focus();
        }

        function hapusBaris(rowId, btn) {
            daftarBukuKolektif = daftarBukuKolektif.filter(r => r.rowId !== rowId);
            btn.parentNode.parentNode.remove();
        }

        function batalSesi() { if(confirm("Batalkan sesi peminjaman?")) location.reload(); }

        function simpanKeExcel() {
            if (daftarBukuKolektif.length === 0) { alert('Belum ada buku.'); return; }
            const payload = {
                tingkat_pendidikan: document.getElementById('tingkat-pendidikan').value,
                nama_sekolah: document.getElementById('nama-sekolah').value,
                penanggung_jawab: document.getElementById('penanggung-jawab').value,
                items: daftarBukuKolektif,
            };
            fetch('simpan_kolektif.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) })
            .then(res => res.json())
            .then(hasil => {
                if (hasil.success) {
                    alert(hasil.jumlah_tersimpan + ' data tersimpan. File Excel terunduh.');
                    window.location = 'export_kolektif.php?format=excel';
                    setTimeout(() => location.reload(), 800);
                }
            });
        }

        // ================= DONASI & SERAH TERIMA =================
        function mulaiSesiDonasi() {
            let tglMohon = document.getElementById('meta-tgl-mohon').value.trim();
            let penerima = document.getElementById('meta-penerima').value.trim();
            if(tglMohon === "" || penerima === "") {
                alert("Mohon isi minimal Tanggal Permohonan dan Pihak Menerima!");
                return;
            }
            ['meta-tgl-mohon', 'meta-tgl-terima', 'meta-petugas', 'meta-penerima', 'meta-instansi'].forEach(id => {
                document.getElementById(id).disabled = true;
            });
            event.target.style.display = 'none';
            document.getElementById('panel-form-donasi').style.display = 'block';
            document.getElementById('d-judul').focus();
        }

        function loadDataDonasi() {
            let tglMohon = document.getElementById('meta-tgl-mohon').value;
            let tglTerima = document.getElementById('meta-tgl-terima').value;
            let petugas = document.getElementById('meta-petugas').value;
            let penerima = document.getElementById('meta-penerima').value;
            let instansi = document.getElementById('meta-instansi').value;

            fetch('get_donasi.php')
            .then(res => res.json())
            .then(data => {
                let tbody = document.querySelector('#tabel-donasi-data tbody');
                tbody.innerHTML = '';
                if(data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="12" class="text-center text-muted">Belum ada data buku donasi.</td></tr>';
                    return;
                }
                data.forEach((row, index) => {
                    let ketCol = '';
                    let tglCol = '';
                    
                    if(index === 0) ketCol = `Tanggal Permohonan : ${tglMohon}`;
                    else if(index === 1) ketCol = `Tanggal Penerima : ${tglTerima}`;
                    else if(index === 2) ketCol = `Petugas Yang Menyerahkan : ${petugas}`;
                    else if(index === 3) ketCol = `Pihak Yang Menerima : ${penerima}`;
                    else if(index === 4) ketCol = `Jumlah Buku : ${data.length}`;
                    else if(index === 5) ketCol = instansi;

                    if(index === 0) tglCol = tglTerima || '';

                    tbody.innerHTML += `
                        <tr>
                            <td><b>${ketCol}</b></td>
                            <td>${tglCol}</td>
                            <td class="text-center">${index + 1}</td>
                            <td>${row.judul}</td>
                            <td>${row.pengarang || '-'}</td>
                            <td>${row.penerbit || '-'}</td>
                            <td>${row.tempat_terbit || '-'}</td>
                            <td class="text-center">${row.tahun_terbit || '-'}</td>
                            <td>${row.isbn || '-'}</td>
                            <td>${row.deskripsi_fisik || '-'}</td>
                            <td class="text-center">${row.eksemplar}</td>
                            <td class="text-center"><button class="btn btn-danger btn-xs" onclick="hapusDonasi(${row.id})">Hapus</button></td>
                        </tr>
                    `;
                });
            });
        }

        function simpanDonasi() {
            let payload = {
                judul: document.getElementById('d-judul').value,
                pengarang: document.getElementById('d-pengarang').value,
                penerbit: document.getElementById('d-penerbit').value,
                tempat_terbit: document.getElementById('d-tempat').value,
                tahun_terbit: document.getElementById('d-tahun').value,
                isbn: document.getElementById('d-isbn').value,
                deskripsi_fisik: document.getElementById('d-deskripsi').value,
                eksemplar: document.getElementById('d-eksemplar').value,
                keterangan: document.getElementById('d-keterangan').value,
            };
            if(!payload.judul) { alert('Judul wajib diisi!'); return; }

            fetch('simpan_donasi.php', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(payload) })
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    alert('Buku donasi berhasil disimpan!');
                    document.getElementById('d-judul').value = '';
                    document.getElementById('d-pengarang').value = '';
                    document.getElementById('d-penerbit').value = '';
                    document.getElementById('d-tempat').value = '';
                    document.getElementById('d-isbn').value = '';
                    document.getElementById('d-eksemplar').value = '1';
                    document.getElementById('d-keterangan').value = 'Donasi Masyarakat';
                    document.getElementById('d-judul').focus();
                    loadDataDonasi();
                }
            });
        }

        function hapusDonasi(id) {
            if(confirm('Hapus buku ini?')) {
                fetch('hapus_donasi.php?id=' + id).then(() => loadDataDonasi());
            }
        }

        function loadPreviewSerah() {
            let perpus = document.getElementById('st-perpus').value.trim();
            let kota = document.getElementById('st-kota').value.trim();
            let tglSurat = document.getElementById('st-tgl-surat').value.trim();

            document.getElementById('lbl-judul-header').innerText = perpus ? "DAFTAR BUKU DONASI " + perpus : "DAFTAR BUKU DONASI";
            document.getElementById('lbl-kota').innerText = kota || "...";
            document.getElementById('lbl-tgl-surat').innerText = tglSurat || "...";

            fetch('get_donasi.php')
            .then(res => res.json())
            .then(data => {
                let tbody = document.getElementById('preview-serah-table');
                tbody.innerHTML = '';
                
                if(data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Belum ada data buku donasi.</td></tr>';
                    return;
                }

                let totalEksemplar = 0;

                data.forEach((row, index) => {
                    let eks = parseInt(row.eksemplar) || 1;
                    totalEksemplar += eks;

                    tbody.innerHTML += `
                        <tr>
                            <td class="text-center">${index + 1}</td>
                            <td>${row.judul}</td>
                            <td>${row.pengarang || '-'}</td>
                            <td class="text-center">${eks}</td>
                        </tr>
                    `;
                });

                tbody.innerHTML += `
                    <tr>
                        <td colspan="3" class="text-right" style="font-weight: bold;">JUMLAH</td>
                        <td class="text-center" style="font-weight: bold;">${totalEksemplar}</td>
                    </tr>
                `;
            });
        }
    </script>
</body>
</html>