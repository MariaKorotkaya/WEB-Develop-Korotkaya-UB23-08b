<?php
session_start();
require 'bd.php'; // Подключаем базу данных

// Проверка, что пользователь авторизован и имеет роль 'admin'
//if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//    echo "Доступ ограничен. Только для администраторов.";
//    exit;
//}

// Проверка, что передан параметр ID пользователя
if (!isset($_GET['id'])) {
    echo "Ошибка: не указан ID пользователя.";
    exit;
}

// Получаем данные о пользователе из базы данных
$user_id = $_GET['id'];
$query = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Проверяем, что пользователь существует
if (!$user) {
    echo "Пользователь не найден.";
    exit;
}

// Обработка отправки формы (редактирование данных)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $birthday = $_POST['birthday'];
    $password = $_POST['password']; // Получаем новый пароль

    // Если пароль не пустой, обновляем его
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Хешируем пароль
        $updateQuery = "UPDATE users SET username = :username, email = :email, role = :role, birthday = :birthday, password = :password WHERE id = :id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([
            'username' => $username,
            'email' => $email,
            'role' => $role,
            'birthday' => $birthday,
            'password' => $hashedPassword, // Сохраняем хешированный пароль
            'id' => $user_id
        ]);
    } else {
        // Если пароль не был изменен, обновляем только остальные поля
        $updateQuery = "UPDATE users SET username = :username, email = :email, role = :role, birthday = :birthday WHERE id = :id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([
            'username' => $username,
            'email' => $email,
            'role' => $role,
            'birthday' => $birthday,
            'id' => $user_id
        ]);
    }

    // Перенаправляем обратно на страницу управления пользователями
    header("Location: admin_page.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать пользователя</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c2c2c; /* Темно-серый фон */
            color: #ffffff; /* Белый текст */
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #D5006D; /* Розовый заголовок */
            text-align: center;
        }

        form {
            background-color: #424242; /* Темно-серый фон для формы */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            margin: 0 auto;
            max-width: 400px; /* Максимальная ширина формы */
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="password"], /* Добавлено для поля пароля */
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #6a1b9a; /* Фиолетовая граница */
            border-radius: 4px;
            background-color: #333; /* Темно-серый фон для полей */
            color: #ffffff; /* Белый текст */
        }

        input[type="submit"] {
            background-color: #D5006D; /* Розовый фон кнопки */
            color: #ffffff; /* Белый текст */
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer; /* Указатель при наведении */
            transition: background-color 0.3s; /* Плавный переход */
            width: 100%; /* Ширина кнопки */
        }

        input[type="submit"]:hover {
            background-color: #b0003a; /* Темнее при наведении */
        }

        .back-button {
            display: block; /* Блок для кнопки */
            margin: 20px auto; /* Центрирование кнопки */
            background-color: #6a1b9a; /* Фиолетовый фон для кнопки */
            color: #ffffff; /* Белый текст для кнопки */
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer; /* Указатель при наведении */
            transition: background-color 0.3s; /* Плавный переход */
            text-align: center; /* Центрируем текст */
            width: 200px; /* Ширина кнопки */
        }

        .back-button:hover {
            background-color: #4a0072; /* Темнее при наведении */
        }
    </style>
</head>
<body>

<h1>Редактировать пользователя: <?php echo htmlspecialchars($user['username']); ?></h1>

<form method="POST">
    <label for="username">Имя пользователя:</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    
    <label for="role">Роль:</label>
    <select id="role" name="role" required>
        <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>Пользователь</option>
        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Администратор</option>
    </select>
    
    <label for="birthday">Дата рождения:</label>
    <input type="date" id="birthday" name="birthday" value="<?php echo htmlspecialchars($user['birthday']); ?>" required>
    
    <label for="password">Новый пароль:</label>
    <input type="password" id="password" name="password" placeholder="Введите новый пароль (оставьте пустым, если не хотите менять)">
    
    <input type="submit" value="Сохранить изменения">
</form>

<!-- Кнопка "Вернуться к списку пользователей" -->
<a href="admin_page.php" class="back-button">Вернуться к списку пользователей</a>

</body>
</html>
