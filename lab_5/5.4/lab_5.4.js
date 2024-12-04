document.getElementById('countButton').addEventListener('click', function() {
    const sentence1 = document.getElementById('sentence1').value.trim();
    const sentence2 = document.getElementById('sentence2').value.trim();

    // Функция для очистки текста от знаков препинания и разбивки на слова
    function getWords(sentence) {
        return sentence.toLowerCase().replace(/[.,!?;:()]/g, '').split(/\s+/);
    }

    // Приводим предложения к нижнему регистру, удаляем знаки препинания и разбиваем на слова
    const words1 = getWords(sentence1);
    const words2 = getWords(sentence2);

    // Создаем массив для хранения общих слов
    const commonWords = [];

    // Пробегаем по первому массиву слов
    for (let i = 0; i < words1.length; i++) {
        const word1 = words1[i];
        // Проверяем, содержится ли текущее слово из первого массива во втором массиве
        for (let j = 0; j < words2.length; j++) {
            const word2 = words2[j];
            if (word1 === word2) {
                // Если слово найдено, добавляем его в массив общих слов, если его там еще нет
                if (!commonWords.includes(word1)) {
                    commonWords.push(word1);
                }
                break; // Выходим из внутреннего цикла, так как слово уже найдено
            }
        }
    }

    // Количество общих слов
    const commonCount = commonWords.length;

    // Отображаем количество общих слов
    document.getElementById('commonCount').innerText = commonCount;
});