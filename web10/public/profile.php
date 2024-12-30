<?php
session_start(); // Запускаем сессию

include 'bd.php'; // Подключаем файл с настройками базы данных

$message = ""; // Переменная для сообщений

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Перенаправляем на страницу входа, если пользователь не авторизован
    exit();
}

// Получаем данные пользователя
$user_id = intval($_SESSION['user_id']);
$user_sql = "SELECT username, email, birthday, role FROM users WHERE id = :user_id";

$stmt = $pdo->prepare($user_sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $user_row = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = htmlspecialchars($user_row['username']);
    $email = htmlspecialchars($user_row['email']);
    $birthday = htmlspecialchars($user_row['birthday']);
    $role = htmlspecialchars($user_row['role']);
} else {
    echo "Пользователь не найден.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль пользователя</title>
    <link rel="stylesheet" type="text/css" href="./css/styles_profile.css"> <!-- Подключаем файл стилей -->
</head>
<body>
    <h1>Профиль пользователя</h1>
    <div class="profile-info">
        <p><strong>Имя пользователя:</strong> <?php echo $username; ?></p>
        <p><strong>Email:</strong> <?php echo $email; ?></p>
        <p><strong>Дата рождения:</strong> <?php echo $birthday; ?></p>
        <p><strong>Роль:</strong> <?php echo $role; ?></p>
    </div>

    <a href="edit_profile.php" class="back-button">Редактировать профиль</a>
    <a href="page2.php" class="back-button">Вернуться к каталогу</a>
    <a href="index.php" class="back-button">Выйти</a>
</body>
</html>
