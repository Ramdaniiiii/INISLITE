<?php
// db.php — koneksi database, dipakai bersama oleh semua file di folder ini.
// Kredensial ini HARUS sama dengan yang ada di:
// C:\laragon\www\inlislite3\common\config\main-local.php

$DB_HOST = 'localhost';
$DB_PORT = '3306';       // sesuaikan kalau MySQL Laragon-mu bukan 3306
$DB_NAME = 'inlislite_v3';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die('Koneksi database gagal: ' . $e->getMessage());
}
