<?php
$host = 'MySQL-8.0'; // адрес сервера MySQL
$dbname = 'bd_users'; // имя базы данных
$username = 'root'; // имя пользователя MySQL
$password = ''; // пароль (если используется)

try {
    // Создаем соединение с базой данных
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Устанавливаем режим обработки ошибок
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
    exit;
}

function getUsernameById($user_id) {
    global $conn; // Используем глобальное соединение
    $sql = "SELECT username FROM bd_users.users WHERE id = ?"; // Указываем базу данных
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        return htmlspecialchars($user['username']); // Возвращаем безопасное имя пользователя
    }
    return null; // Если пользователь не найден
}

?>
