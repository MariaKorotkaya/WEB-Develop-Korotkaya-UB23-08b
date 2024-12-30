<?php
session_start(); // Начинаем сессию

include 'items_bd.php'; // Подключаем файл с настройками базы данных
include 'bd.php'; // Подключаем файл с функциями работы с базой данных

$conn = getDbConnection(); // Получаем соединение с базой данных

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $item_id = intval($_POST['item_id']);
    $comment = trim($_POST['comment']);
    
    // Проверяем, есть ли пользователь в сессии
    if (isset($_SESSION['user_id'])) {
        $user_id = intval($_SESSION['user_id']);
        $author = getUsernameById($user_id); // Получаем имя пользователя
    } else {
        // Если пользователь не авторизован, используем 'Гость'
        $author = 'Гость';
    }

    // Добавляем комментарий в базу данных
    $stmt = $conn->prepare("INSERT INTO comments (item_id, author, comment, created_at) VALUES (?, ?, ?, NOW())");
    
    if ($stmt === false) {
        // Проверка на ошибки при подготовке запроса
        echo "Ошибка подготовки запроса: " . $conn->error;
        exit();
    }

    $stmt->bind_param("iss", $item_id, $author, $comment);

    if ($stmt->execute()) {
        // Успешное добавление комментария
        header("Location: page2.php"); // Перенаправляем на страницу каталога
        exit();
    } else {
        // Ошибка при добавлении комментария
        echo "Ошибка: " . $stmt->error;
    }

    $stmt->close(); // Закрываем подготовленный запрос
}

closeDbConnection($conn); // Закрываем соединение с базой данных
?>
