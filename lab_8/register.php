<?php
// Подключаем конфигурационный файл для работы с БД
require_once '../config/config.inc.php';  // Путь к файлу config.inc.php


// Проверка, если форма отправлена методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы и проверяем на пустоту
    $full_name = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';  
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $birth_date = isset($_POST['birthday']) ? $_POST['birthday'] : '';  

    // Проверка на пустые поля
    if (empty($full_name) || empty($username) || empty($password) || empty($birth_date)) {
        $message = "Все поля обязательны для заполнения.";
    } else {
        // Получаем соединение с базой данных
        $conn = getDbConnection();

        // Проверка, есть ли уже пользователь с таким логином (username)
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Ошибка: Логин уже занят!";
        } else {
            // Хэшируем пароль перед сохранением
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Вставка данных в таблицу users
            $stmt = $conn->prepare("INSERT INTO users (fullname, username, password, birthday) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $full_name, $username, $hashed_password, $birth_date);

            if ($stmt->execute()) {
                $message = "Регистрация прошла успешно!";
            } else {
                $message = "Ошибка при регистрации: " . $stmt->error;
            }
        }

        // Закрытие соединения
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма регистрации</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2e2d2; /* Бежевый фон страницы */
        }
        .container {
            width: 400px;
            margin: 50px auto;
            background-color: #fff7f2; /* Светло-бежевый фон формы */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #d6b9a8; /* Легкая коричневая рамка */
        }
        h2 {
            text-align: center;
            color: #6a4f34; /* Темно-коричневый цвет для заголовка */
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #6a4f34; /* Темно-коричневый цвет для текста */
        }
        input[type="text"],
        input[type="password"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #d6b9a8; /* Коричневая рамка */
            border-radius: 4px;
            background-color: #f9f1e1; /* Светлый фон для инпутов */
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #a67458; /* Коричневая кнопка */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #8c5d42; /* Темнее при наведении */
        }
        .message {
            color: red;
            font-weight: bold;
            text-align: center;
        }
        .success-message {
            color: green;
            font-weight: bold;
            text-align: center;
        }
        .back-button {
            width: 100%;
            padding: 10px;
            background-color: #d6b9a8; /* Коричневая кнопка "Назад" */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 15px;
        }
        .back-button:hover {
            background-color: #a67458; /* Темнее при наведении */
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Регистрация</h2>

    <!-- Выводим сообщение об успехе или ошибке -->
    <?php if (isset($message)) { 
        // Если регистрация успешна, делаем текст зеленым
        $messageClass = (strpos($message, 'успешно') !== false) ? 'success-message' : 'message';
        echo "<div class='$messageClass'>$message</div>"; 
    } ?>

    <form method="POST" action="register.php">
        <label for="fullname">Полное имя:</label>
        <input type="text" id="fullname" name="fullname" placeholder="Введите ваше полное имя" required>

        <label for="username">Логин:</label>
        <input type="text" id="username" name="username" placeholder="Введите логин" required>

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" placeholder="Введите пароль" required>

        <label for="birthday">Дата рождения:</label>
        <input type="date" id="birthday" name="birthday" required>

        <input type="submit" value="Зарегистрироваться">
    </form>

    <!-- Кнопка назад -->
    <form action="javascript:history.back()">
        <input type="submit" class="back-button" value="Назад">
    </form>
</div>

</body>
</html>
