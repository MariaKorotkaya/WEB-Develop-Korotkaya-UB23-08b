<?php
include 'items_bd.php'; // Подключаем файл с настройками базы данных

$conn = getDbConnection(); // Получаем соединение с базой данных

// Проверяем, был ли передан ID объекта для редактирования
if (isset($_GET['id'])) {
    $item_id = intval($_GET['id']);
    
    // Получаем данные объекта из базы данных
    $sql = "SELECT * FROM items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
    } else {
        echo "Объект не найден.";
        exit;
    }
    
    $stmt->close();
} else {
    echo "ID объекта не указан.";
    exit;
}

// Обработка формы редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверяем, была ли нажата кнопка удаления
    if (isset($_POST['delete_id'])) {
        // Удаляем объект из базы данных
        $delete_sql = "DELETE FROM items WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $item_id);
        
        if ($delete_stmt->execute()) {
            echo "<p style='color: #4CAF50;'>Объект удален успешно!</p>";
            header("Location: index.php"); // Перенаправление на главную страницу
            exit;
        } else {
            echo "<p style='color: red;'>Ошибка при удалении: " . $delete_stmt->error . "</p>";
        }
        
        $delete_stmt->close();
    } else {
        // Обновляем данные объекта в базе данных
        $edit_name = $_POST['name'];
        $edit_description = $_POST['description'];
        $edit_image = $_POST['image'];

        $edit_sql = "UPDATE items SET name = ?, description = ?, image = ? WHERE id = ?";
        $edit_stmt = $conn->prepare($edit_sql);
        $edit_stmt->bind_param("sssi", $edit_name, $edit_description, $edit_image, $item_id);
        
        if ($edit_stmt->execute()) {
            echo "<p style='color: #4CAF50;'>Объект обновлен успешно!</p>";
        } else {
            echo "<p style='color: red;'>Ошибка при обновлении: " . $edit_stmt->error . "</p>";
        }
        
        $edit_stmt->close();
    }
}
?>

<h1>Редактировать объект</h1>
<form method="POST">
    <input type="text" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required />
    <input type="text" name="description" value="<?php echo htmlspecialchars($item['description']); ?>" required />
    <input type="text" name="image" value="<?php echo htmlspecialchars($item['image']); ?>" placeholder="URL изображения" />
    <button type="submit" style="background-color: #4CAF50; color: white; border: none; border-radius: 4px; padding: 5px 10px; cursor: pointer;">Сохранить изменения</button>
</form>

<form method="POST">
    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($item['id']); ?>" />
    <button type="submit" style="background-color: #d5006d; color: white; border: none; border-radius: 4px; padding: 5px 10px; cursor: pointer;">Удалить объект</button>
</form>

<a href="page2.php" style="text-decoration: none; padding: 10px; background-color: #ccc; color: black; border-radius: 5px;">Назад к каталогу</a>

<?php
closeDbConnection($conn); // Закрываем соединение с базой данных
?>
