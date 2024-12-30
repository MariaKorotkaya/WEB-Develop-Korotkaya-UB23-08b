<?php
// config/config.inc.php

// Конфигурация для первой базы данных
define('DB1_HOST', 'MySQL-8.0');
define('DB1_NAME', 'bd_users');
define('DB1_USER', 'root'); // по умолчанию
define('DB1_PASS', ''); // если установлен пароль, введите его

// Конфигурация для второй базы данных
define('DB2_HOST', 'MySQL-8.0');
define('DB2_NAME', 'catalog_bd');
define('DB2_USER', 'root'); // по умолчанию
define('DB2_PASS', ''); // если установлен пароль, введите его

function getDbConnection() {
    $conn = new mysqli(DB2_HOST, DB2_USER, DB2_PASS, DB2_NAME);
    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }
    return $conn;
}

function closeDbConnection($conn) {
    $conn->close();
}
?>