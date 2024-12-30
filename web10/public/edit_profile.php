<?php
session_start(); // Запускаем сессию

include 'bd.php'; // Подключаем файл с настройками базы данных

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Перенаправляем на страницу входа, если пользователь не авторизован
    exit();
}

// Получаем данные пользователя
$user_id = intval($_SESSION['user_id']);
$message = "";

// Обработка формы при отправке
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $birthday = trim($_POST['birthday']);

    // Валидация данных
    if (empty($username) || empty($email) || empty($birthday)) {
        $message = "Все поля обязательны для заполнения.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Некорректный формат email.";
    } else {
        // Обновление данных в базе данных
        $update_sql = "UPDATE users SET username = :username, email = :email, birthday = :birthday WHERE id = :user_id";
        $stmt = $pdo->prepare($update_sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':birthday', $birthday);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $message = "Данные успешно обновлены.";
        } else {
            $message = "Ошибка при обновлении данных.";
        }
    }
}

// Получаем текущие данные пользователя для заполнения формы
$user_sql = "SELECT username, email, birthday FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($user_sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_row = $stmt->fetch(PDO::FETCH_ASSOC);

$username = htmlspecialchars($user_row['username']);
$email = htmlspecialchars($user_row['email']);
$birthday = htmlspecialchars($user_row['birthday']);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование профиля</title>
    <link rel="stylesheet" type="text/css" href="./css/styles_edit_profile.css"> <!-- Подключаем файл стилей -->
</head>
<body>
    <h1>Редактирование профиля</h1>
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <div class="form-container">
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Имя пользователя:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label for="birthday">Дата рождения:</label>
                <input type="date" id="birthday" name="birthday" value="<?php echo $birthday; ?>" required>
            </div>
            <button type="submit" class="submit-button">Сохранить изменения</button>
        </form>
    </div>

    <a href="profile.php" class="submit-button">Вернуться к профилю</a>
</body>
</html>
