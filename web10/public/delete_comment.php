<?php
session_start(); // Убедитесь, что сессия запущена

include 'items_bd.php'; // Подключаем файл с настройками базы данных

$conn = getDbConnection(); // Получаем соединение с базой данных

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'])) {
    $comment_id = intval($_POST['comment_id']);

    // Запрос на удаление комментария
    $sql = "DELETE FROM comments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $comment_id);

    if ($stmt->execute()) {
        // Успешное удаление
        echo "Комментарий удален.";
    } else {
        // Ошибка при удалении
        echo "Ошибка при удалении комментария: " . $conn->error;
    }

    $stmt->close();
}

// Перенаправление обратно на страницу с объектами
header("Location: page2.php"); // Замените на имя вашей страницы с объектами
exit;

closeDbConnection($conn); // Закрываем соединение с базой данных
?>
