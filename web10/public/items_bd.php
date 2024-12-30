<?php
// items_bd.php

define('DB_HOST', 'MySQL-8.0'); // Хост базы данных
define('DB_NAME', 'catalog_bd'); // Имя базы данных
define('DB_USER', 'root'); // Имя пользователя базы данных
define('DB_PASS', ''); // Пароль пользователя базы данных

function getDbConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }
    return $conn;
}

function closeDbConnection($conn) {
    $conn->close();
}

// Функция для получения всех объектов из таблицы items
function getAllItems($conn) {
    $sql = "SELECT * FROM items";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); // Возвращаем все объекты как ассоциативный массив
    } else {
        return []; // Возвращаем пустой массив, если объектов нет
    }
}

// Функция для получения комментариев по идентификатору объекта
function getCommentsByItemId($conn, $item_id) {
    $sql = "SELECT * FROM comments WHERE item_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id); // Привязываем параметр
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); // Возвращаем все комментарии как ассоциативный массив
    } else {
        return []; // Возвращаем пустой массив, если комментариев нет
    }
}

// Функция для добавления нового комментария
function addComment($conn, $item_id, $comment, $author) {
    $sql = "INSERT INTO comments (item_id, comment, author) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $item_id, $comment, $author); // Привязываем параметры
    return $stmt->execute(); // Возвращаем результат выполнения
}
?>
