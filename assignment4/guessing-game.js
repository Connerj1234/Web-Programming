const MAX_GUESSES = 10;

let secretNumber;
let guessesRemaining;
let roundSeconds;
let timerInterval;

const guessInput = document.getElementById('guess-input');
const guessButton = document.getElementById('guess-button');
const feedback = document.getElementById('feedback');
const guessesRemainingSpan = document.getElementById('guesses-remaining');
const roundTimerSpan = document.getElementById('round-timer');
const newGameButton = document.getElementById('new-game-button');

const soundCorrect = document.getElementById('sound-correct');
const soundWrong = document.getElementById('sound-wrong');

function startNewGame() {
    secretNumber = Math.floor(Math.random() * 100) + 1;
    guessesRemaining = MAX_GUESSES;
    roundSeconds = 0;

    guessesRemainingSpan.textContent = guessesRemaining;
    roundTimerSpan.textContent = '0s';
    feedback.textContent = 'New round started. Make your first guess.';
    feedback.className = 'feedback';

    guessInput.value = '';
    guessInput.disabled = false;
    guessButton.disabled = false;

    if (timerInterval) {
        clearInterval(timerInterval);
    }
    timerInterval = setInterval(() => {
        roundSeconds += 1;
        roundTimerSpan.textContent = `${roundSeconds}s`;
    }, 1000);
}

function stopTimer() {
    if (timerInterval) {
        clearInterval(timerInterval);
        timerInterval = null;
    }
}

function playSound(audioElement) {
    if (!audioElement) return;
    try {
        audioElement.currentTime = 0;
        audioElement.play();
    } catch (e) {
    }
}

function handleGuess() {
    const value = Number(guessInput.value);

    if (!Number.isInteger(value) || value < 1 || value > 100) {
        feedback.textContent = 'Please enter a whole number between 1 and 100.';
        feedback.className = 'feedback feedback-error';
        playSound(soundWrong);
        return;
    }

    if (guessesRemaining <= 0) {
        return;
    }

    guessesRemaining -= 1;
    guessesRemainingSpan.textContent = guessesRemaining;

    if (value === secretNumber) {
        stopTimer();
        feedback.textContent =
            `Correct. The secret number was ${secretNumber}. Starting a new game.`;
        feedback.className = 'feedback feedback-success';
        playSound(soundCorrect);
        setTimeout(startNewGame, 1500);
        return;
    }

    if (value < secretNumber) {
        feedback.textContent = 'Too low. Try a higher number.';
    } else {
        feedback.textContent = 'Too high. Try a lower number.';
    }
    feedback.className = 'feedback feedback-info';
    playSound(soundWrong);

    if (guessesRemaining === 0) {
        stopTimer();
        feedback.textContent =
            `Out of guesses. The secret number was ${secretNumber}. Starting a new game.`;
        feedback.className = 'feedback feedback-error';
        guessInput.disabled = true;
        guessButton.disabled = true;

        setTimeout(startNewGame, 2000);
    }
}

guessButton.addEventListener('click', handleGuess);

guessInput.addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
        handleGuess();
    }
});

newGameButton.addEventListener('click', () => {
    stopTimer();
    startNewGame();
});

// start first round when page loads
startNewGame();
