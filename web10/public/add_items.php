<?php
include 'items_bd.php'; // Подключаем файл с настройками базы данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получаем данные из формы
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_POST['image'];

    $conn = getDbConnection(); // Получаем соединение с базой данных
    
    // Подготавливаем SQL-запрос для вставки данных
    $sql = "INSERT INTO items (name, description, image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $description, $image);

    // Выполняем запрос и проверяем результат
    if ($stmt->execute()) {
        echo "<p style='color: #4CAF50;'>Объект добавлен успешно!</p>";
    } else {
        echo "<p style='color: red;'>Ошибка: " . $stmt->error . "</p>";
    }

    $stmt->close(); // Закрываем подготовленный запрос
    closeDbConnection($conn); // Закрываем соединение с базой данных
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить объект</title>
    <style>
        body {
            background-color: #2c2c2c; /* Темно-серый фон */
            color: white; /* Белый текст */
            font-family: Arial, sans-serif; /* Шрифт */
            margin: 0;
            padding: 20px;
        }
        h1, h2 {
            color: #d5006d; /* Розовый цвет для заголовков */
        }
        form {
            background-color: #4a148c; /* Фиолетовый фон формы */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px; /* Отступ снизу для кнопки "Назад" */
        }
        input[type="text"], textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 4px;
            background-color: #2c2c2c; /* Серый цвет для полей ввода */
            color: white; /* Белый текст в полях */
        }
        button {
            background-color: #d5006d; /* Розовая кнопка */
            color: white; /* Белый текст на кнопке */
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #c51162; /* Темнее при наведении */
        }
        .back-button {
            background-color: #4a148c; /* Фиолетовый цвет для кнопки "Назад" */
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            color: white;
            text-decoration: none; /* Убираем подчеркивание */
            display: inline-block; /* Для правильного отображения */
            margin-top: 10px; /* Отступ сверху */
        }
        .back-button:hover {
            background-color: #3e0f6b; /* Темнее при наведении */
        }
    </style>
</head>
<body>

<h1>Добавить новый объект</h1>

<!-- Форма для добавления новых объектов -->
<form method="POST" action="">
    <input type="text" name="name" placeholder="Название" required>
    <textarea name="description" placeholder="Описание" required></textarea>
    <input type="text" name="image" placeholder="Ссылка на изображение" required>
    <button type="submit">Добавить объект</button>
</form>

<!-- Кнопка "Назад" -->
<a href="page2.php" class="back-button">Назад</a>

</body>
</html>
