let display = document.getElementById('display');
let currentInput = '';
let lastInputWasOperator = false;

// Функция для добавления числа или десятичной точки
function appendNumber(number) {
    // Если это точка, проверим, не была ли она уже добавлена
    if (number === '.' && currentInput.includes('.')) return;

    // Если после оператора идет точка, это недопустимо
    if (lastInputWasOperator && number === '.') return;

    currentInput += number.toString();
    updateDisplay();
    lastInputWasOperator = false;
}

// Функция для добавления оператора
function appendOperator(operator) {
    if (currentInput === '' || lastInputWasOperator) return; // Запрещаем операторы в начале или после оператора
    currentInput += ` ${operator} `;
    updateDisplay();
    lastInputWasOperator = true;
}

// Очистка дисплея
function clearDisplay() {
    currentInput = '';
    updateDisplay();
}

// Очистка последнего введенного символа
function clearLast() {
    currentInput = currentInput.slice(0, -1);
    updateDisplay();
    lastInputWasOperator = false; // После удаления символа не может быть оператора
}

// Вычисление результата
function calculateResult() {
    if (currentInput === '') return;

    try {
        // Заменяем проценты на деление на 100 для корректных вычислений
        currentInput = currentInput.replace(/%/g, '/100');
        
        // Проверка на деление на ноль
        if (currentInput.includes('/ 0') || currentInput.includes('* Infinity') || currentInput.includes('/ Infinity')) {
            currentInput = 'Ошибка'; // Деление на 0 или бесконечность
        } else {
            // Вычисление с помощью eval
            currentInput = eval(currentInput).toString();
        }
    } catch (error) {
        currentInput = 'Ошибка'; // Если выражение некорректно
    }
    updateDisplay();
    lastInputWasOperator = false;
}

// Обновление дисплея
function updateDisplay() {
    display.value = currentInput;
}
