# Cara Pasang — Modul Inventaris Buku (PHP Polos, di dalam inlislite3)

Semua file ini **satu proyek** dengan InlisLite — pakai database yang sama
(`inlislite_v3`), tanpa Laravel, tanpa Composer tambahan.

## 1. Copy folder ini ke dalam inlislite3

Salin seluruh isi paket ini ke:
```
C:\laragon\www\inlislite3\inventaris\
```
Jadi strukturnya:
```
C:\laragon\www\inlislite3\
├── index.php              <- punya InlisLite (jangan diubah)
├── frontend\ backend\ ...  <- punya InlisLite
└── inventaris\             <- folder BARU dari paket ini
    ├── index.php
    ├── db.php
    ├── simpan_inventaris.php
    ├── export_inventaris.php
    ├── simpan_kolektif.php
    ├── export_kolektif.php
    ├── excel_writer.php
    └── inventaris_tables.sql
```

## 2. Buat 2 tabel baru di database

Buka HeidiSQL, pastikan database **inlislite_v3** aktif, lalu jalankan isi file
`inventaris_tables.sql` (Load SQL file → Execute/F9, seperti waktu import data
InlisLite dulu). Ini hanya MENAMBAH 2 tabel baru (`inventaris_buku` dan
`peminjaman_kolektif`) — tidak menyentuh tabel InlisLite yang sudah ada.

## 3. Cek kredensial database

Buka `db.php`, pastikan isinya sama persis dengan
`C:\laragon\www\inlislite3\common\config\main-local.php` punyamu
(host, port, nama database, user, password). Kalau MySQL Laragon-mu bukan
port 3306, sesuaikan di sini juga.

## 4. Buka & coba

Akses: `http://localhost/inlislite3/inventaris/`

- Tab **Inventaris Buku**: isi form → klik **Simpan** → data langsung masuk
  ke database, form otomatis bersih dengan No. Inventaris baru untuk buku
  berikutnya (tidak perlu reload halaman). **Export Excel**/**Export PDF**
  bisa diklik kapan saja, akan mengunduh SEMUA data yang pernah tersimpan.
- Tab **Peminjaman Kolektif**: isi data sekolah sekali → **Mulai Input Buku**
  → scan/ketik No. Inventaris tiap buku, klik **Tambah** (bisa berkali-kali)
  → setelah semua buku masuk daftar, klik **Simpan & Export Excel** — semua
  baris tersimpan sekaligus ke database lalu file Excel terunduh otomatis.

## 5. Hubungkan dari Portal InlisLite

Buka `C:\laragon\www\inlislite3\frontend\views\site\index.php`, cari variabel:
```php
$urlInventaris = '/inventaris';
```
Ganti jadi:
```php
$urlInventaris = 'inventaris/';
```
(path relatif, karena sekarang folder `inventaris` memang ada persis di
dalam `inlislite3`)

## Catatan teknis
- Excel dibuat TANPA library composer apapun — format "Excel 2003 XML",
  dibuka normal oleh Microsoft Excel, disimpan dengan ekstensi `.xls`.
- PDF dibuat dengan cara membuka halaman cetak siap-print di tab baru;
  dialog print/save-as-PDF browser akan otomatis muncul.
- Nomor No. Inventaris dibuat otomatis (`00033519` → `00033520` → dst)
  dengan mengambil nomor tertinggi yang sudah tersimpan +1.
- Kalau nanti mau menambah/mengubah kolom, edit di 3 tempat: `index.php`
  (form), `simpan_inventaris.php`/`simpan_kolektif.php` (validasi & insert),
  dan `export_inventaris.php`/`export_kolektif.php` (kolom laporan).
