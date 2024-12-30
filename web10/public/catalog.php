<?php
session_start(); // Убедитесь, что сессия запущена

include 'items_bd.php'; // Подключаем файл с настройками базы данных

$conn = getDbConnection(); // Получаем соединение с базой данных

// Получаем все объекты из базы данных
$sql = "SELECT * FROM items";
$result = $conn->query($sql);

echo '<link rel="stylesheet" type="text/css" href="./css/styles_catalog.css">'; // Подключаем файл стилей

if ($result->num_rows > 0) {
    echo "<h1>Каталог объектов</h1>";
    echo "<div class='container'>"; // Создаем контейнер для двух колонок
    while ($row = $result->fetch_assoc()) {
        echo "<div class='item'>"; // Таблица для каждого объекта
        echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
        echo "<p>" . htmlspecialchars($row['description']) . "</p>";
        
        if ($row['image']) {
            // Изображение с выравниванием по центру
            echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' />"; // Фиксированные размеры для изображений
        }

        // Проверяем, является ли пользователь администратором
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            // Кнопка для редактирования
            echo "<a href='functions.php?id=" . htmlspecialchars($row['id']) . "' class='button-edit'>Редактировать</a>"; // Фиолетовый цвет и позиционирование
            
            // Кнопка для удаления комментария
            echo "<form action='delete_comment.php' method='POST' style='display:inline; position: absolute; right: 10px; top: 10px;'>
                    <input type='hidden' name='item_id' value='" . intval($row['id']) . "' />
                    <button type='submit' class='button-delete'>Удалить</button>
                  </form>";
        }

        // Получаем и отображаем комментарии
        $comments_sql = "SELECT * FROM comments WHERE item_id = " . intval($row['id']) . " ORDER BY created_at DESC";
        $comments_result = $conn->query($comments_sql);

        if ($comments_result->num_rows > 0) {
            echo "<h4>Комментарии:</h4>";
            while ($comment = $comments_result->fetch_assoc()) {
                echo "<div class='comment'>"; // Оформление комментария
                echo "<p style='margin: 0;'><strong>" . htmlspecialchars($comment['author']) . ":</strong> " . htmlspecialchars($comment['comment']) . " <em>(" . $comment['created_at'] . ")</em></p>";
                
                // Кнопка для удаления комментария (только для админов)
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                    echo "<form action='delete_comment.php' method='POST' style='display:inline; position: absolute; right: 10px; top: 10px;'>
                            <input type='hidden' name='comment_id' value='" . intval($comment['id']) . "' />
                            <button type='submit' class='button-delete'>Удалить</button>
                          </form>";
                }
                echo "</div>"; // Закрываем блок комментария
            }
        } else {
            echo "<p>Нет комментариев.</p>";
        }

        // Форма для добавления комментария (перемещена под все комментарии)
        echo "<h3>Добавить комментарий:</h3>";
        echo "<form action='add_comment.php' method='POST' class='comment-form' style='margin-top: 10px;'>";
        echo "<input type='hidden' name='item_id' value='" . htmlspecialchars($row['id']) . "' />";
        echo "<textarea name='comment' required placeholder='Ваш комментарий'></textarea>";

        // Проверяем, есть ли имя пользователя в сессии
        if (isset($_SESSION['username'])) {
            echo "<input type='hidden' name='author' value='" . htmlspecialchars($_SESSION['username']) . "' />"; // Скрытое поле для имени пользователя
        } else {
            echo "<input type='hidden' name='author' value='Гость' />"; // Если пользователя нет, используем 'Гость'
        }

        // Кнопка отправки с оформлением
        echo "<button type='submit' class='button-submit'>Отправить</button>
              </form>";

        echo "</div>"; // Закрываем таблицу для объекта
    }
    echo "</div>"; // Закрываем контейнер для колонок
} else {
    echo "Нет объектов в каталоге.";
}

// Кнопка для добавления новых объектов (только для админов)
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    echo '<h2><a href="add_items.php" class="button-submit">Добавить новые объекты</a></h2>'; // Розовый цвет
}

closeDbConnection($conn); // Закрываем соединение с базой данных
?>
