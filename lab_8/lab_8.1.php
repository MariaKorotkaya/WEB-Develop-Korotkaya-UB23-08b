<?php
// Функция для вывода таблицы умножения от 0 до 10
function printMultiplicationTable() {
    $size = 10; // Устанавливаем размер таблицы от 0 до 10
    echo "<table class='multiplication-table'>";
    echo "<thead><tr><th>*</th>";

    // Заголовок столбцов
    for ($i = 0; $i <= $size; $i++) {
        echo "<th>$i</th>";
    }
    echo "</tr></thead><tbody>";

    // Заполнение таблицы умножения
    for ($i = 0; $i <= $size; $i++) {
        echo "<tr><th>$i</th>";
        for ($j = 0; $j <= $size; $j++) {
            $result = $i * $j;
            echo "<td>$result</td>";
        }
        echo "</tr>";
    }
    echo "</tbody></table>";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Таблица умножения</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f1e1; 
            color: #4e3629; 
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        h1 {
            color: #4e3629;
            font-size: 3em;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .multiplication-table {
            width: 90%;
            max-width: 1000px;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            background-color: #fff8e1; 
            border-radius: 8px;
            overflow: hidden;
            margin: 0 auto;
        }

        .multiplication-table th, .multiplication-table td {
            padding: 15px; 
            border: 1px solid #d2b48c; 
            text-align: center;
            font-size: 1.4em; 
        }

        .multiplication-table th {
            background-color: #d2b48c; 
            color: #4e3629; 
            font-weight: bold;
        }

        .multiplication-table td {
            background-color: #f9f3e1; 
            color: #4e3629; 
        }

        .multiplication-table tr:nth-child(even) td {
            background-color: #f5e6cc;
        }

        .multiplication-table tr:hover th,
        .multiplication-table tr:hover td {
            background-color: #e9d8b6;
            cursor: pointer;
        }

        /* Мобильные стили */
        @media (max-width: 768px) {
            .multiplication-table th, .multiplication-table td {
                font-size: 1.2em; /* Меньший размер текста на мобильных устройствах */
                padding: 12px;
            }

            h1 {
                font-size: 2.2em;
            }
        }
    </style>
</head>
<body>

<div>
    <h1>Таблица умножения от 0 до 10</h1>

    <?php
    // Выводим таблицу умножения
    printMultiplicationTable();
    ?>
</div>

</body>
</html>
