const difficultyConfig = {
    easy: { memorizeSeconds: 8 },
    medium: { memorizeSeconds: 5 },
    hard: { memorizeSeconds: 3 }
};

const timeLimitByPairs = {
    8: 120,
    10: 150,
    12: 180
};

const POINTS_MATCH = 10;
const POINTS_MISS = -5;
const POINTS_PER_SECOND_OVER = -1;

const difficultySelect = document.getElementById('difficulty');
const pairCountSelect = document.getElementById('pair-count');
const startGameButton = document.getElementById('start-game');

const bgColorInput = document.getElementById('bg-color');
const cardColorInput = document.getElementById('card-color');
const accentColorInput = document.getElementById('accent-color');

const imageUploadInput = document.getElementById('image-upload');
const imageUploadStatus = document.getElementById('image-upload-status');

const memorizeTimeSpan = document.getElementById('memorize-time');
const gameTimeSpan = document.getElementById('game-time');
const scoreSpan = document.getElementById('score');
const statusMessage = document.getElementById('status-message');

const gameBoard = document.getElementById('game-board');
const leaderboardList = document.getElementById('leaderboard-list');
const clearLeaderboardButton = document.getElementById('clear-leaderboard');

let customImages = []; // array of data URLs
let cards = [];
let firstCard = null;
let secondCard = null;
let matchesFound = 0;
let score = 0;

let memorizeTimerId = null;
let gameTimerId = null;
let gameElapsedSeconds = 0;
let gameStarted = false;

const LEADERBOARD_KEY = 'memoryGameLeaderboard';

const defaultImagePlaceholders = [
    'images/9830e492-bef5-444a-87f5-27a3fc9a8503-owl-worlds-cutest-baby-animals-ranked.jpg',
    'images/20241030-817A8773-16RP.jpg',
    'images/Adelie-penguin.webp',
    'images/elephant-facts-you-may-not-know.1819cadc.jpg',
    'images/farm-animals-animal-sentience-header.jpg',
    'images/Giraffe.jpg',
    'images/golden-retriever-tongue-out.jpg',
    'images/Lion.jpg',
    'images/pexels-pixabay-47547.jpg',
    'images/shouts-animals-watch-baby-hemingway.webp',
    'images/Zebra.jpg',
    'images/Elephant.jpg'
];

// ---------------- Theme handling ----------------
function applyTheme() {
    const root = document.documentElement;
    root.style.setProperty('--bg-color', bgColorInput.value);
    root.style.setProperty('--card-color', cardColorInput.value);
    root.style.setProperty('--accent-color', accentColorInput.value);
}

bgColorInput.addEventListener('input', applyTheme);
cardColorInput.addEventListener('input', applyTheme);
accentColorInput.addEventListener('input', applyTheme);

// ---------------- Image upload handling ----------------
imageUploadInput.addEventListener('change', () => {
    const files = Array.from(imageUploadInput.files || []);
    if (files.length === 0) {
        customImages = [];
        imageUploadStatus.textContent = 'No files selected.';
        return;
    }

    customImages = [];
    imageUploadStatus.textContent = 'Loading images...';

    let processedCount = 0;
    let validCount = 0;

    files.forEach(file => {
        if (!file.type.startsWith('image/')) {
            processedCount += 1;
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            processedCount += 1;
            return;
        }

        const reader = new FileReader();
        reader.onload = () => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                const size = 200;
                canvas.width = size;
                canvas.height = size;
                const ctx = canvas.getContext('2d');

                const minSide = Math.min(img.width, img.height);
                const sx = (img.width - minSide) / 2;
                const sy = (img.height - minSide) / 2;
                ctx.drawImage(img, sx, sy, minSide, minSide, 0, 0, size, size);

                const dataUrl = canvas.toDataURL('image/png');
                customImages.push(dataUrl);
                validCount += 1;
                processedCount += 1;
                if (processedCount === files.length) {
                    imageUploadStatus.textContent =
                        `Loaded ${validCount} valid image(s). They will be used if enough for the selected number of pairs.`;
                }
            };
            img.src = reader.result;
        };
        reader.readAsDataURL(file);
    });
});

// ---------------- Game generation ----------------
function shuffleArray(arr) {
    for (let i = arr.length - 1; i > 0; i -= 1) {
        const j = Math.floor(Math.random() * (i + 1));
        const temp = arr[i];
        arr[i] = arr[j];
        arr[j] = temp;
    }
}

function createCardElement(card) {
    const cardDiv = document.createElement('div');
    cardDiv.className = 'memory-card';
    cardDiv.dataset.id = card.id;
    cardDiv.dataset.index = card.index;

    const front = document.createElement('div');
    front.className = 'card-face card-front';
    const img = document.createElement('img');
    img.src = card.imageSrc;
    img.alt = `Card image ${card.id}`;
    front.appendChild(img);

    const back = document.createElement('div');
    back.className = 'card-face card-back';
    back.textContent = card.displayNumber.toString();

    cardDiv.appendChild(front);
    cardDiv.appendChild(back);

    cardDiv.addEventListener('click', () => handleCardClick(cardDiv));

    return cardDiv;
}

function buildDeck(numPairs) {
    const imagesToUse = [];

    if (customImages.length >= numPairs) {
        for (let i = 0; i < numPairs; i += 1) {
            imagesToUse.push(customImages[i]);
        }
    } else {
        for (let i = 0; i < numPairs; i += 1) {
            imagesToUse.push(defaultImagePlaceholders[i % defaultImagePlaceholders.length]);
        }
    }

    const deck = [];
    for (let i = 0; i < numPairs; i += 1) {
        const imageSrc = imagesToUse[i];
        const cardA = { id: i, imageSrc, displayNumber: i + 1 };
        const cardB = { id: i, imageSrc, displayNumber: i + 1 };
        deck.push(cardA, cardB);
    }
    shuffleArray(deck);

    // attach index
    deck.forEach((card, index) => {
        card.index = index;
    });

    return deck;
}

function renderBoard(deck) {
    gameBoard.innerHTML = '';
    cards = deck;
    matchesFound = 0;
    firstCard = null;
    secondCard = null;

    const cardCount = deck.length;
    let columns;
    if (cardCount === 16) columns = 4;
    else if (cardCount === 20) columns = 5;
    else columns = 6;

    gameBoard.style.gridTemplateColumns = `repeat(${columns}, minmax(70px, 1fr))`;

    deck.forEach(card => {
        const cardElem = createCardElement(card);
        gameBoard.appendChild(cardElem);
    });
}

// ---------------- Timers and rounds ----------------
function resetTimers() {
    if (memorizeTimerId) {
        clearTimeout(memorizeTimerId);
        memorizeTimerId = null;
    }
    if (gameTimerId) {
        clearInterval(gameTimerId);
        gameTimerId = null;
    }
    memorizeTimeSpan.textContent = '0s';
    gameTimeSpan.textContent = '0s';
}

function startGame() {
    applyTheme();

    const difficulty = difficultySelect.value;
    const numPairs = Number(pairCountSelect.value);
    const memorizeSeconds = difficultyConfig[difficulty].memorizeSeconds;
    const gameTimeLimit = timeLimitByPairs[numPairs];

    resetTimers();

    score = 0;
    scoreSpan.textContent = score.toString();
    statusMessage.textContent = 'Memorize the positions of the images.';
    statusMessage.className = 'feedback feedback-info';

    const deck = buildDeck(numPairs);
    renderBoard(deck);

    // show front (images) for memorize period
    setCardMode('images-only');
    gameStarted = false;
    gameElapsedSeconds = 0;

    memorizeTimeSpan.textContent = `${memorizeSeconds}s`;
    gameTimeSpan.textContent = '0s';

    memorizeTimerId = setTimeout(() => {
        // flip to numbers and start timer
        setCardMode('numbers');
        startMainTimer(gameTimeLimit);
        statusMessage.textContent = 'Find all matching pairs before time runs out.';
        statusMessage.className = 'feedback feedback-info';
        gameStarted = true;
    }, memorizeSeconds * 1000);
}

function startMainTimer(timeLimitSeconds) {
    gameElapsedSeconds = 0;
    gameTimerId = setInterval(() => {
        gameElapsedSeconds += 1;
        gameTimeSpan.textContent = `${gameElapsedSeconds}s`;

        if (gameElapsedSeconds > timeLimitSeconds) {
            score += POINTS_PER_SECOND_OVER;
            scoreSpan.textContent = score.toString();
        }
    }, 1000);
}

// mode: 'images-only' for memorize, 'numbers' for play
function setCardMode(mode) {
    const cardElements = gameBoard.querySelectorAll('.memory-card');
    cardElements.forEach(cardElem => {
        if (mode === 'images-only') {
            cardElem.classList.add('show-front');
            cardElem.classList.remove('flipped');
        } else if (mode === 'numbers') {
            cardElem.classList.remove('show-front');
            cardElem.classList.remove('flipped');
        }
    });
}

// ---------------- Card click handling ----------------
let clickLocked = false;

function handleCardClick(cardElem) {
    if (!gameStarted || clickLocked) return;
    if (cardElem.classList.contains('matched')) return;

    // if clicking same card twice
    if (firstCard && firstCard === cardElem) return;

    flipToFront(cardElem);

    if (!firstCard) {
        firstCard = cardElem;
    } else if (!secondCard) {
        secondCard = cardElem;
        checkMatch();
    }
}

function flipToFront(cardElem) {
    cardElem.classList.add('flipped');
}

function flipToBack(cardElem) {
    cardElem.classList.remove('flipped');
}

function checkMatch() {
    const id1 = firstCard.dataset.id;
    const id2 = secondCard.dataset.id;

    const isMatch = id1 === id2;

    if (isMatch) {
        score += POINTS_MATCH;
        scoreSpan.textContent = score.toString();

        firstCard.classList.add('matched');
        secondCard.classList.add('matched');

        matchesFound += 1;
        firstCard = null;
        secondCard = null;

        const totalPairs = Number(pairCountSelect.value);
        if (matchesFound === totalPairs) {
            endGame(true);
        }
    } else {
        score += POINTS_MISS;
        scoreSpan.textContent = score.toString();
        clickLocked = true;
        statusMessage.textContent = 'Not a match. Try again.';
        statusMessage.className = 'feedback feedback-error';

        setTimeout(() => {
            flipToBack(firstCard);
            flipToBack(secondCard);
            firstCard = null;
            secondCard = null;
            clickLocked = false;
        }, 900);
    }
}

// ---------------- Game end and leaderboard ----------------
function endGame(completedAllPairs) {
    gameStarted = false;
    resetTimers();

    if (completedAllPairs) {
        statusMessage.textContent = `You matched all pairs. Final score: ${score}.`;
        statusMessage.className = 'feedback feedback-success';
    } else {
        statusMessage.textContent = `Time is up. Final score: ${score}.`;
        statusMessage.className = 'feedback feedback-error';
    }

    const name = prompt('Game over. Enter your name for the leaderboard:', 'Player');
    if (name && name.trim().length > 0) {
        saveScore(name.trim(), score);
    }
}

function saveScore(name, scoreValue) {
    const stored = localStorage.getItem(LEADERBOARD_KEY);
    const list = stored ? JSON.parse(stored) : [];

    list.push({ name, score: scoreValue, date: new Date().toISOString() });

    list.sort((a, b) => b.score - a.score);
    const topFive = list.slice(0, 5);

    localStorage.setItem(LEADERBOARD_KEY, JSON.stringify(topFive));
    renderLeaderboard();
}

function renderLeaderboard() {
    const stored = localStorage.getItem(LEADERBOARD_KEY);
    const list = stored ? JSON.parse(stored) : [];
    leaderboardList.innerHTML = '';

    if (list.length === 0) {
        const li = document.createElement('li');
        li.textContent = 'No scores saved yet. Play a game to add your first score.';
        leaderboardList.appendChild(li);
        return;
    }

    list.forEach((entry, index) => {
        const li = document.createElement('li');
        li.className = 'leaderboard-item';
        li.innerHTML = `<span class="rank">${index + 1}.</span>
                        <span class="leaderboard-name">${entry.name}</span>
                        <span class="leaderboard-score">${entry.score}</span>`;
        leaderboardList.appendChild(li);
    });
}

clearLeaderboardButton.addEventListener('click', () => {
    localStorage.removeItem(LEADERBOARD_KEY);
    renderLeaderboard();
});

// ---------------- Event wiring ----------------
startGameButton.addEventListener('click', () => {
    startGame();
});

renderLeaderboard();
applyTheme();
