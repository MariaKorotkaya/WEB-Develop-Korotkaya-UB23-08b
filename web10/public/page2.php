<?php
session_start();
require 'bd.php'; // Подключение к базе данных
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления</title>
    <link rel="stylesheet" type="text/css" href="./css/styles_page2.css"> <!-- Подключаем файл стилей -->
    <script>
        function loadPage(page) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', page, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('interactive-window').innerHTML = this.responseText;
                } else {
                    document.getElementById('interactive-window').innerHTML = '<p>Ошибка загрузки страницы.</p>';
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>

    <div class="sidebar">
        <h3>Меню</h3>
        <a href="#" onclick="loadPage('history.php'); return false;">Узнать историю</a>
        <a href="#" onclick="loadPage('catalog.php'); return false;">Узнать подробнее о легендарных машинах</a>
        <a href="#" class="profile-button" onclick="loadPage('profile.php'); return false;">Профиль</a> <!-- Выделенная кнопка "Профиль" -->
    </div>

    <div class="content">
        <div class="header">
            <h2>Панель управления</h2>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin_page.php" class="admin-button">Просмотр пользователей</a>
            <?php endif; ?>
        </div>
        <p>Добро пожаловать на панель управления!</p>
        <div id="interactive-window" class="interactive-window">
            <p>Выберите пункт из меню, чтобы увидеть содержимое.</p>
        </div>
    </div>

    <script>
        // Загружаем содержимое catalog.php при первоначальной загрузке страницы
        loadPage('catalog.php');
    </script>
</body>
</html>
