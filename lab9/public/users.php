<?php
session_start();
require 'bd.php'; // Подключение к базе данных

// Проверка, является ли пользователь администратором
$isAdmin = isset($_SESSION['user_id']) && /* Ваша логика для проверки роли администратора */;
if (!$isAdmin) {
    header('Location: page2.php'); // Перенаправление, если не администратор
    exit;
}

// Обработка добавления пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $newUsername = $_POST['new_username'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $newRole = $_POST['new_role'];

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
    $stmt->execute(['username' => $newUsername, 'password' => $newPassword, 'role' => $newRole]);
}

// Обработка удаления пользователя
if (isset($_GET['delete'])) {
    $userId = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
}

// Получение списка пользователей
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пользователи</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #D5006D;
            color: white;
        }
    </style>
</head>
<body>

<h2>Список пользователей</h2>

<form method="POST">
    <h3>Добавить пользователя</h3>
    <input type="text" name="new_username" placeholder="Логин" required>
    <input type="password" name="new_password" placeholder="Пароль" required>
    <select name="new_role">
        <option value="user">Пользователь</option>
        <option value="admin">Администратор</option>
    </select>
    <button type="submit" name="add_user">Добавить</button>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Логин</th>
            <th>Роль</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['role']); ?></td>
            <td>
                <a href="users.php?delete=<?php echo $user['id']; ?>" style="color: red;">Удалить</a>
                <!-- Добавьте сюда возможность редактирования ролей или других данных -->
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
