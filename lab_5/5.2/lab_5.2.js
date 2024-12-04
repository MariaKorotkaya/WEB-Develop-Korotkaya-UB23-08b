document.getElementById('calculateButtonA').addEventListener('click', calculateProduct);
document.getElementById('calculateButtonB').addEventListener('click', processArray);

function calculateProduct() {
    const input = document.getElementById('arrayInputA').value;
    const numbers = input.split(',').map(Number);
    let product = 1;
    let foundNegativeCosine = false;

    for (let i = 0; i < numbers.length; i++) {
        if (Math.cos(numbers[i]) < 0) {
            foundNegativeCosine = true;
            break;
        }
        product *= numbers[i];
    }

    if (foundNegativeCosine) {
        document.getElementById('resultA').textContent = `Произведение элементов до первого отрицательного косинуса: ${product}`;
    } else {
        
        document.getElementById('resultA').textContent = `Нет отрицательных косинусов в массиве. Произведение всех элементов: ${product}`;
    }
}

function isDecreasingProgression(num) {
    const integerPart = Math.abs(Math.floor(num)).toString(); // Берем модуль и целую часть числа
    const digits = integerPart.split('').map(Number); // Преобразуем в массив цифр

    // Проверяем, образуют ли цифры убывающую арифметическую прогрессию
    for (let i = 1; i < digits.length; i++) {
        if (digits[i] >= digits[i - 1]) {
            return false; // Если не строго убывающая, возвращаем false
        }
    }
    
    return true; // Все цифры образуют убывающую прогрессию
}

function processArray() {
    const input = document.getElementById('arrayInputB').value;
    const numbers = input.split(',').map(Number);

    // Фильтруем массив, оставляя только те элементы, которые не удовлетворяют условию
    const resultArray = numbers.filter(num => !isDecreasingProgression(num));
    
    document.getElementById('resultB').textContent = `Результирующий массив: ${resultArray.join(', ')}`;
}