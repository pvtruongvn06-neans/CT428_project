
<?php

try {
    // Nap file ket noi PDO
    require_once __DIR__ . '/config/database.php';

    // Kiem tra ten CSDL dang ket noi
    $stmt = $pdo->query('SELECT DATABASE() AS db_name');
    $result = $stmt->fetch();

    echo 'Ket noi MySQL thanh cong!<br>';
    echo 'CSDL dang su dung: '
        . htmlspecialchars($result['db_name'], ENT_QUOTES, 'UTF-8')
        . '<br>';

    // Liet ke cac bang trong CSDL
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);

    echo '<br>Cac bang hien co:<br>';

    foreach ($tables as $table) {
        echo '- ' . htmlspecialchars($table, ENT_QUOTES, 'UTF-8') . '<br>';
    }
} catch (Throwable $e) {
    // Chi ghi loi chi tiet vao log may chu
    error_log($e->__toString());

    http_response_code(500);
    echo 'Ket noi that bai. Hay kiem tra cau hinh MySQL va log loi.';
}
