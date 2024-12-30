<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="./css/styles_register.css"> <!-- Подключение CSS-файла -->
</head>
<body>
    <div class="register-container">
        <h2>Регистрация</h2>

        <?php
        // Подключение к базе данных
        require 'bd.php';

        // Переменные для сообщений
        $errorMessage = '';
        $successMessage = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email']; // Получение email
            $password = $_POST['password'];
            $birthday = $_POST['birthday'];
            $role = 'user'; // По умолчанию роль пользователя

            // Проверка на уникальность логина
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
            $stmt->execute(['username' => $username, 'email' => $email]);
            $existingUser   = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingUser  ) {
                $errorMessage = "Логин или почта уже заняты. Пожалуйста, выберите другой.";
            } else {
                // Хеширование пароля
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Вставка нового пользователя в базу данных
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, birthday, role) VALUES (:username, :email, :password, :birthday, :role)");
                $stmt->execute([
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashedPassword,
                    'birthday' => $birthday,
                    'role' => $role
                ]);

                // Перенаправление на page2.php после успешной регистрации
                header("Location: page2.php");
                exit(); // Завершение выполнения скрипта
            }
        }
        ?>

        <!-- Вывод сообщения об ошибке, если оно есть -->
        <?php if ($errorMessage): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="email" name="email" placeholder="Электронная почта" required> <!-- Поле для ввода email -->
            <input type="password" name="password" placeholder="Пароль" required>
            <input type="date" name="birthday" placeholder="Дата рождения" required>
            <button type="submit" class="register-button">Зарегистрироваться</button>
        </form>
        <div class="login-link" style="text-align: center; margin-top: 10px;">
            <p>Уже есть аккаунт?</p>
            <a href="login.php" style="color: #D5006D; text-decoration: none;">Войти</a>
        </div>
    </div>
</body>
</html>
