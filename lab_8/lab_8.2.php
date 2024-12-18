<?php

// Функция для вывода календаря
function printCalendar($month = null, $year = null) {
    // Если параметры не заданы, используем текущий месяц и год
    if (!$month) {
        $month = date('m');
    }
    if (!$year) {
        $year = date('Y');
    }

    // Определяем количество дней в месяце
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    // Первый день месяца
    $firstDay = strtotime("$year-$month-01");

    // Сдвигаем день недели, чтобы неделя начиналась с понедельника
    $startDayOfWeek = date('w', $firstDay);
    $startDayOfWeek = ($startDayOfWeek == 0) ? 6 : $startDayOfWeek - 1; // Если воскресенье (0), то делаем его 6 (воскресенье)

    // Праздничные дни (добавлены 8 марта, 23 февраля, 9 мая, 31 декабря и 1 января)
    $holidays = [
        "$year-01-01", // Новый год
        "$year-02-23", // День защитника Отечества
        "$year-03-08", // Международный женский день
        "$year-05-09", // День Победы
        "$year-12-31", // Новый год (вечер)
        "$year-12-25", // Рождество
    ];

    // Начинаем выводить календарь
    echo "<table class='calendar-table'>";

    // Заголовок календаря
    echo "<tr><th colspan='7'>" . date('F Y', strtotime("$year-$month-01")) . "</th></tr>";
    
    // Дни недели, начиная с понедельника
    echo "<tr>
            <th>Пн</th>
            <th>Вт</th>
            <th>Ср</th>
            <th>Чт</th>
            <th>Пт</th>
            <th>Сб</th>
            <th>Вс</th>
          </tr>";
    
    // Начинаем заполнять календарь
    $day = 1;

    // Добавляем пустые ячейки до первого дня месяца
    echo "<tr>";
    for ($i = 0; $i < $startDayOfWeek; $i++) {
        echo "<td class='empty'>&nbsp;</td>";
    }

    // Заполняем дни месяца
    for ($i = $startDayOfWeek; $i < 7; $i++) {
        if ($day <= $daysInMonth) {
            $date = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
            // Проверяем, выходной ли это день
            $isWeekend = ($i == 5 || $i == 6); // Суббота или Воскресенье
            // Проверяем, является ли день праздничным
            $isHoliday = in_array($date, $holidays);
            
            // Определяем класс для ячейки
            $class = '';
            if ($isHoliday) {
                $class = 'holiday'; // Праздничный день
            } elseif ($isWeekend) {
                $class = 'weekend'; // Выходной день
            }

            echo "<td class='$class'>$day</td>";
            $day++;
        } else {
            echo "<td class='empty'>&nbsp;</td>";
        }
    }
    echo "</tr>";

    // Заполняем оставшиеся недели месяца
    while ($day <= $daysInMonth) {
        echo "<tr>";
        for ($i = 0; $i < 7; $i++) {
            if ($day <= $daysInMonth) {
                $date = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                // Проверяем, выходной ли это день
                $isWeekend = ($i == 5 || $i == 6); // Суббота или Воскресенье
                // Проверяем, является ли день праздничным
                $isHoliday = in_array($date, $holidays);
                
                // Определяем класс для ячейки
                $class = '';
                if ($isHoliday) {
                    $class = 'holiday'; // Праздничный день
                } elseif ($isWeekend) {
                    $class = 'weekend'; // Выходной день
                }

                echo "<td class='$class'>$day</td>";
                $day++;
            } else {
                echo "<td class='empty'>&nbsp;</td>";
            }
        }
        echo "</tr>";
    }

    echo "</table>";
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Календарь</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f3e6; 
        }

        table {
            border-collapse: collapse;
            width: 50%;
            margin: 20px auto;
        }

        th, td {
            border: 1px solid #8B4513; 
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #d2b48c; 
            color: #5c3a20; 
        }

        td {
            background-color: #ffe4c4; 
        }

        td:nth-child(even) {
            background-color: #f5deb3; 
        }

        .form-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px; 
            background-color: #d2b48c; 
            color: #5c3a20; 
            text-decoration: none;
            border: 1px solid #8B4513; 
            border-radius: 5px; 
            font-weight: bold; 
            text-align: center; 
            transition: background-color 0.3s, color 0.3s; 
        }

        .button:hover {
            background-color: #ffe4c4; 
            color: #3b2a16; 
        }


        .holiday {
            background-color: #fff8c2; 
            color: #5c3a20;
        }

        .weekend {
            background-color: #ffbc89; 
            color: #fff;
        }

        .empty {
            background-color: #f9f9f9;
        }

    </style>
</head>
<body>

<div class="form-container">
    <h2>Введите месяц и год для отображения календаря</h2>
    <form method="GET">
        <label for="month">Месяц:</label>
        <input type="number" name="month" id="month" value="<?php echo isset($_GET['month']) ? $_GET['month'] : date('m'); ?>" min="1" max="12" required>
        <label for="year">Год:</label>
        <input type="number" name="year" id="year" value="<?php echo isset($_GET['year']) ? $_GET['year'] : date('Y'); ?>" min="1900" required>
        <input type="submit" value="Показать календарь" class="button">
    </form>
</div>

<?php
// Получаем месяц и год из GET запроса
$month = isset($_GET['month']) ? intval($_GET['month']) : null;
$year = isset($_GET['year']) ? intval($_GET['year']) : null;

// Выводим календарь
printCalendar($month, $year);
?>

</body>
</html>
