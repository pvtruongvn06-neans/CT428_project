
<?php

// 1. Thong tin ket noi MySQL
$host = 'localhost';
$dbname = 'jobio';
$username = 'root';
$password = '';
$charset = 'utf8mb4';

// 2. Tao chuoi mo ta ket noi (DSN)
$dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

// 3. Cac tuy chon cho PDO
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// 4. Tao ket noi PDO
$pdo = new PDO($dsn, $username, $password, $options);
