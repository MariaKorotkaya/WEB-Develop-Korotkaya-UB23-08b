<?php
session_start();
require 'bd.php'; // Подключаем базу данных

// Проверка, что пользователь авторизован и имеет роль 'admin'
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     echo "Доступ ограничен. Только для администраторов.";
//     exit;
// }

// Обработка добавления нового пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Хешируем пароль
    $birthday = $_POST['birthday'];
    $role = $_POST['role'];  // Предполагается, что вы также передаете роль пользователя

    $insertQuery = "INSERT INTO users (username, email, password, birthday, role) VALUES (:username, :email, :password, :birthday, :role)";
    $insertStmt = $pdo->prepare($insertQuery);
    $insertStmt->execute([
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'birthday' => $birthday,
        'role' => $role
    ]);

    // Перенаправление обратно на страницу управления пользователями
    header("Location: admin_page.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить пользователя</title>
    <link rel="stylesheet" type="text/css" href="./css/styles_add_user.css"> <!-- Подключаем файл стилей -->
</head>
<body>

<h1>Добавить пользователя</h1>

<form method="POST">
    <label for="username">Имя пользователя:</label>
    <input type="text" id="username" name="username" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="email">Пароль:</label>
    <input type="password" name="password" placeholder="Пароль" required>

    <label for="birthday">Дата рождения:</label>
    <input type="date" id="birthday" name="birthday" required>

    <label for="role">Роль:</label>
    <select id="role" name="role" required>
        <option value="user">Пользователь</option>
        <option value="admin">Администратор</option>
    </select>

    <button type="submit">Добавить пользователя</button>
</form>

<!-- Кнопка "Вернуться на страницу управления пользователями" -->
<a href="admin_page.php" class="back-button">Вернуться на страницу управления пользователями</a>

</body>
</html>
