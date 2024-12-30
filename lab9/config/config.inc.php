<?php
// config/config.inc.php

// Конфигурация для первой базы данных
define('DB1_HOST', 'MySQL-8.0');
define('DB1_NAME', 'bd_users');
define('DB1_USER', 'root'); // по умолчанию
define('DB1_PASS', ''); // если установлен пароль, введите его

function closeDbConnection($conn) {
    $conn->close();
}
?>