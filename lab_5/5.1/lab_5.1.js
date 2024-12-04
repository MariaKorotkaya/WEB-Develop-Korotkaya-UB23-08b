let userNumber, computerGuess, attempts;

function startGame() {
    userNumber = parseInt(document.getElementById('user-number').value);
    if (userNumber < 1 || userNumber > 100 || isNaN(userNumber)) {
        
        alert("Пожалуйста, введите число от 1 до 100.");
        return;
    }

    attempts = [];
    document.getElementById('attempts-body').innerHTML = '';
    document.getElementById('game').classList.remove('hidden');
    document.getElementById('end-message').classList.add('hidden');
    nextGuess();
}

function nextGuess() {
    if (attempts.length === 0) {
        computerGuess = Math.floor(Math.random() * 100) + 1;
    } else {
        // Уменьшаем диапазон
        let min = 1;
        let max = 100;

        attempts.forEach(attempt => {
            if (attempt.status === 'too-low') {
                min = Math.max(min, attempt.guess + 1);
            } else if (attempt.status === 'too-high') {
                max = Math.min(max, attempt.guess - 1);
            }
        });

        computerGuess = Math.floor(Math.random() * (max - min + 1)) + min;
    }

    attempts.push({ guess: computerGuess, status: '' });
    document.getElementById('current-guess').textContent = computerGuess;
    addAttemptToTable(computerGuess);
}

function addAttemptToTable(guess) {
    const row = document.createElement('tr');
    const cell = document.createElement('td');
    cell.textContent = guess;
    row.appendChild(cell);
    document.getElementById('attempts-body').appendChild(row);
}

function giveFeedback(feedback) {
    const lastAttempt = attempts[attempts.length - 1];
    lastAttempt.status = feedback;

    if (feedback === 'too-low') {
        nextGuess();
    } else if (feedback === 'too-high') {
        nextGuess();
    } else if (feedback === 'correct') {
        document.getElementById('end-message').classList.remove('hidden');
        document.getElementById('game').classList.add('hidden');
        alert(`Компьютер угадал ваше число: ${computerGuess}!`);
    }
}

function resetGame() {
    document.getElementById('user-number').value = '';
    document.getElementById('game').classList.add('hidden');
    document.getElementById('end-message').classList.add('hidden');
}
