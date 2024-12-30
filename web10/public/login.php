<?php
// Начинаем сессии
session_start();

// Подключение к базе данных
require 'bd.php';

// Переменная для хранения сообщения об ошибке
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Проверка пользователя
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Успешная авторизация
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header('Location: page2.php'); // Перенаправление на page2.php
        exit;
    } else {
        $errorMessage = "Неверный логин или пароль!";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему</title>
    <link rel="stylesheet" href="./css/styles_login.css"> <!-- Подключение CSS-файла -->
    <script>
        function showModal() {
            document.getElementById("myModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

        function sendCode() {
            const email = document.getElementById("email").value;
            const code = Math.floor(100 + Math.random() * 900); // Генерация трехзначного кода
            alert("Код подтверждения отправлен на почту: " + email + "\nВаш код: " + code);
            // Здесь вы можете добавить код для отправки письма с кодом на почту
            document.getElementById("code").value = code; // Для тестирования, устанавливаем код в поле
        }

        function verifyCode() {
            const enteredCode = document.getElementById("enteredCode").value;
            const actualCode = document.getElementById("code").value;
            if (enteredCode === actualCode) {
                alert("Код подтверждения верен! Теперь вы можете установить новый пароль.");
                // Здесь вы можете добавить логику для отображения поля для ввода нового пароля
                document.getElementById("newPasswordContainer").style.display = "block";
            } else {
                alert("Неверный код подтверждения!");
            }
        }
    </script>
</head>
<body>
    <div class="login-container">
        <h2>Вход</h2>

        <!-- Вывод сообщения об ошибке, если оно есть -->
        <?php if ($errorMessage): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Логин" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit" class="login-button">Войти</button>
        </form>
        <button class="forgot-password-button" onclick="showModal()">Забыл пароль?</button>
        <div class="register-link">
            <p>Вы у нас впервые?</p>
            <a href="register.php" style="color: #D5006D; text-decoration: none;">Зарегистрироваться</a>
        </div>
    </div>

    <!-- Модальное окно для восстановления пароля -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Восстановление пароля</h3>
            <input type="email" id="email" placeholder="Введите вашу почту" required>
            <button onclick="sendCode()" class="login-button">Отправить код</button>
            <input type="hidden" id="code"> <!-- Скрытое поле для хранения кода -->

            <h4>Введите код подтверждения:</h4>
            <input type="text" id="enteredCode" placeholder="Код" required>
            <button onclick="verifyCode()" class="login-button">Подтвердить код</button>

            <div id="newPasswordContainer" style="display: none;">
                <h4>Введите новый пароль:</h4>
                <input type="password" id="newPassword" placeholder="Новый пароль" required>
                <button onclick="alert('Пароль успешно изменен!')" class="login-button">Сохранить новый пароль</button>
            </div>
        </div>
    </div>
</body>
</html>
