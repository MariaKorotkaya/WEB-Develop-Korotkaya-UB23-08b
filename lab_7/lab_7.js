let display = document.getElementById('display');
let currentInput = '';

function appendNumber(number) {
    currentInput += number.toString();
    updateDisplay();
}

function appendOperator(operator) {
    if (currentInput === '') return;
    currentInput += ` ${operator} `;
    updateDisplay();
}

function clearDisplay() {
    currentInput = '';
    updateDisplay();
}

function clearLast() {
    currentInput = currentInput.slice(0, -1);
    updateDisplay();
}

function changeSign() {
    if (currentInput === '') return;
    currentInput =
    (parseFloat(currentInput) * -1).toString();
    updateDisplay();
}

function calculateSqrt() {
    if (currentInput === '') return;
    const result = Math.sqrt(parseFloat(currentInput));
    currentInput = result.toString();
    updateDisplay();
}

function calculateInverse() {
    if (currentInput === '') return;
    const result = 1 / parseFloat(currentInput);
    currentInput = result.toString();
    updateDisplay();
}

function calculateResult() {
    if (currentInput === '') return;
    try {
        currentInput = eval(currentInput.replace(/%/g, '/100')).toString();
        updateDisplay();
    } catch {
        currentInput = 'Ошибка';
        updateDisplay();
    }
}

function updateDisplay() {
    display.value = currentInput;
}