document.getElementById('credit-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Предотвращаем отправку формы

    // Сбор данных из формы
    const fullName = document.getElementById('full-name').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const email = document.getElementById('email').value.trim();
    const passport = document.getElementById('passport').value.trim();
    const dob = document.getElementById('dob').value;
    const loanPurpose = document.getElementById('loan-purpose').value.trim();
    const amount = document.getElementById('amount').value.trim();
    const term = document.getElementById('term').value;
    const income = document.getElementById('income').value.trim();
    const agreement = document.getElementById('agreement').checked;

    // Проверка обязательных полей
    if (!fullName || !phone || !email || !passport || !dob || !loanPurpose || !amount || !term || !income || !agreement) {
        alert('Пожалуйста, заполните все обязательные поля.');
        return;
    }

    // Проверка ФИО на наличие цифр
    const nameRegex = /^[А-ЯЁа-яё\s\-]+$/;
    if (!nameRegex.test(fullName)) {
        alert("ФИО не должно содержать цифр.");
        return;
    }

    // Проверка формата телефона на регулярное выражение
    const phonePattern = /^\+\d{1,3}\s\d{3}\s\d{3}-\d{2}-\d{2}$/;
    if (!phonePattern.test(phone)) {
        alert('Пожалуйста, введите корректный номер телефона в формате +X XXX XXX-XX-XX.');
        return;
    }

    // Проверка корректности формата электронной почты
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Пожалуйста, введите корректный адрес электронной почты.');
        return;
    }

    // Формируем текст для проверки данных
    const checkText = `
        <strong>ФИО:</strong> ${fullName}<br>
        <strong>Телефон:</strong> ${phone}<br>
        <strong>Электронная почта:</strong> ${email}<br>
        <strong>Паспортные данные:</strong> ${passport}<br>
        <strong>Дата рождения:</strong> ${dob}<br>
        
        <strong>Цель кредита:</strong> ${loanPurpose}<br>
        <strong>Сумма кредита:</strong> ${amount} рублей<br>
        <strong>Срок кредита:</strong> ${term} месяцев<br>
        <strong>Ежемесячный доход:</strong> ${income} рублей
    `;
    document.getElementById('checkText').innerHTML = checkText;

    // Показываем модальное окно с проверкой данных
    const checkModal = document.getElementById("checkModal");
    checkModal.style.display = "block";

    // Закрытие модального окна при нажатии кнопки "Закрыть" (X)
    const closeButton = document.getElementsByClassName("close")[0];
    closeButton.onclick = function() {
        checkModal.style.display = "none";
    };

    // Закрытие модального окна при нажатии на кнопку "Подтвердить"
    document.getElementById('confirmButton').onclick = function() {
        checkModal.style.display = "none";
        alert("Заявка отправлена!"); // Сообщение о том, что заявка отправлена
        
        // Очистка формы
        document.getElementById('credit-form').reset();
    };

    // Обработка кнопки "Изменить"
    document.getElementById('editButton').onclick = function() {
        checkModal.style.display = "none"; // Закрываем модальное окно
    };
});

// Закрытие модального окна при нажатии вне его
window.onclick = function(event) {
    const checkModal = document.getElementById("checkModal");
    if (event.target == checkModal) {
        checkModal.style.display = "none";
    }
};
