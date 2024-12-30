<?php
session_start();
require 'bd.php'; // Подключаем базу данных

// Проверка, что пользователь авторизован и имеет роль 'admin'
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     echo "Доступ ограничен. Только для администраторов.";
//     exit;
// }

// Обработка удаления пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];

    // Подготовленный запрос для удаления пользователя
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(['id' => $deleteId]);

    // Вы можете добавить сообщение об успешном удалении
    echo "<script>alert('Пользователь успешно удален.');</script>";
}

// Запрос на выборку всех пользователей
$query = "SELECT * FROM users";
$stmt = $pdo->query($query);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c2c2c; /* Темно-серый фон */
            color: #ffffff; /* Белый текст */
            margin: 0;
            padding: 20px;
            position: relative; /* Для позиционирования кнопки */
        }

        h1 {
            color: #D5006D; /* Розовый заголовок */
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #4a4a4a; /* Темно-серый разделитель */
        }

        th {
            background-color: #6a1b9a; /* Фиолетовый фон для заголовков */
            color: #ffffff; /* Белый текст для заголовков */
        }

        tr:nth-child(even) {
            background-color: #424242; /* Темно-серый фон для четных строк */
        }

        tr:hover {
            background-color: #D5006D; /* Розовый фон при наведении */
        }

        a {
            color: #D5006D; /* Розовый цвет для ссылок */
            text-decoration: none; /* Убираем подчеркивание */
            padding: 8px 12px;
            border: 2px solid #D5006D; /* Розовая граница */
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s; /* Плавный переход */
        }

        a:hover {
            background-color: #D5006D; /* Розовый фон при наведении на ссылку */
            color: #ffffff; /* Белый текст при наведении */
        }

        .delete-button {
            background-color: transparent; /* Прозрачный фон */
            color: #D5006D; /* Розовый текст */
            border: 2px solid #D5006D; /* Розовая граница */
            border-radius: 4px;
            padding: 8px 12px;
            cursor: pointer; /* Указатель при наведении */
            transition: background-color 0.3s, color 0.3s; /* Плавный переход */
            text-decoration: none; /* Убираем подчеркивание */
        }

        .delete-button:hover {
            background-color: #D5006D; /* Розовый фон при наведении */
            color: #ffffff; /* Белый текст при наведении */
        }

        .back-button {
            display: block; /* Блок для кнопки */
            margin: 20px auto; /* Центрирование кнопки */
            background-color: #D5006D; /* Розовый фон для кнопки */
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
            background-color: #b0003a; /* Темнее при наведении */
        }
    </style>
</head>
<body>

<h1>Управление пользователями</h1>

<!-- Кнопка "Добавить пользователя" -->
<a href="add_user.php" class="back-button">Добавить пользователя</a>

<table>
    <tr>
        <th>ID</th>
        <th>Имя пользователя</th>
        <th>Email</th>
        <th>Дата рождения</th>
        <th>Роль</th>
        <th>Действия</th>
    </tr>

    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['birthday']); ?></td>
            <td><?php echo htmlspecialchars($user['role']); ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $user['id']; ?>">Редактировать</a>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" class="delete-button">Удалить</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<a href="page2.php" class="back-button">Вернуться на основную страницу</a>

</body>
</html>
