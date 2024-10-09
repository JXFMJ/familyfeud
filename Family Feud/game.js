// Variables to track the game state
let player1Score = 0;
let player2Score = 0;
let currentRound = 1;
const maxRounds = 5;
let currentAnswer = '';
let gameActive = false;

// Game data object
const gameData = {
    player1: '',
    player2: '',
    correctAnswers: ['apple', 'banana', 'orange', 'grape'], // Pre-stored answers for demo
    roundScores: []
};

// Start Game event listener
document.getElementById('start-game').addEventListener('click', startGame);

// Event listener for form submission
document.getElementById('answer-form').addEventListener('submit', function (e) {
    e.preventDefault();
    if (gameActive) {
        validateAnswer();
    }
});

// Function to initialize the game
function startGame() {
    console.log('Game started');
    gameActive = true;

    // Get player names
    gameData.player1 = document.getElementById('team1-player').value;
    gameData.player2 = document.getElementById('team2-player').value;

    // Reset scores and round
    player1Score = 0;
    player2Score = 0;
    currentRound = 1;

    // Clear results and update scores
    document.getElementById('results').innerHTML = '';
    updateScores();

    // Start the countdown timer
    startCountdown();

    // Begin the first round
    nextRound();
}

// Function to validate answers
function validateAnswer() {
    const userAnswer = document.getElementById('answer').value.toLowerCase();
    if (gameData.correctAnswers.includes(userAnswer)) {
        displayCorrectAnswer(userAnswer);
    } else {
        alert('Incorrect answer!');
    }
}

// Function to display correct answers
function displayCorrectAnswer(answer) {
    const resultDiv = document.getElementById('results');
    resultDiv.innerHTML += `<p>Correct Answer: ${answer}</p>`;
    updateScores();
}

// Function to update player scores in the table
function updateScores() {
    const tableBody = document.querySelector('tbody');
    tableBody.innerHTML = `
        <tr>
            <td>Score: ${player1Score}</td>
            <td>Score: ${player2Score}</td>
        </tr>
    `;
}

// Countdown timer using setInterval()
function startCountdown() {
    let timeLeft = 10; // 10 seconds countdown
    const timerInterval = setInterval(function () {
        document.getElementById('timer-value').textContent = timeLeft;
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            endRound();
        }
        timeLeft--;
    }, 1000);
}

// Function to handle the end of a round
function endRound() {
    console.log('Round ended');
    if (currentRound >= maxRounds) {
        gameOver();
    } else {
        currentRound++;
        nextRound();
    }
}

// Function to start a new round
function nextRound() {
    console.log(`Starting round ${currentRound}`);
    alert(`Round ${currentRound} started!`);
    startCountdown();
}

// Random event function (e.g., buzzer pressed)
function randomEvent() {
    const buzzer = Math.random() > 0.5 ? 'pressed' : 'not pressed';
    console.log(`Buzzer was ${buzzer}`);
}

// Game over function
function gameOver() {
    gameActive = false;
    console.log('Game over');
    alert(`Game Over! Final Scores:\nPlayer 1: ${player1Score}\nPlayer 2: ${player2Score}`);
    resetGame();
}

// Reset game after completion
function resetGame() {
    console.log('Resetting game...');
    player1Score = 0;
    player2Score = 0;
    currentRound = 1;
    document.getElementById('results').innerHTML = '';
    updateScores();
    gameActive = false;
}

// Function to handle buzzer timing using setTimeout
function handleBuzzerTiming() {
    setTimeout(function () {
        console.log('Buzzer timing event triggered');
        randomEvent();
    }, Math.random() * 5000); // Random delay between 0-5 seconds
}

// Use console.log() for debugging
console.log('Game script loaded');

// Event listeners for round-specific buttons
document.getElementById('pass-button').addEventListener('click', function () {
    alert('Pass to next player!');
});

document.getElementById('play-button').addEventListener('click', function () {
    alert('Play the game!');
});
