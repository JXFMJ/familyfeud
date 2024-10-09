<?php
session_start();

// Database connection
$host = 'localhost'; // Your database host
$username = 'root'; // Your database username
$password = ''; // Your database password
$dbname = 'family_feud'; // Your database name

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to sanitize user input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the answer form was submitted
    if (isset($_POST['answer'])) {
        $playerName = sanitizeInput($_SESSION['player_name']); // Assuming player name is stored in session
        $score = 0; // Default score, to be updated based on the game logic
        
        // Here you would add logic to validate the answer and update the score
        // For this example, we assume the player gets 10 points for each correct answer
        $correctAnswer = "example"; // Replace with actual answer checking logic
        if (strtolower($_POST['answer']) == $correctAnswer) {
            $score += 10; // Increase score for correct answer
            echo "Correct Answer! You scored 10 points.";
        } else {
            echo "Incorrect Answer! No points scored.";
        }

        // Insert the score into the database
        $stmt = $conn->prepare("INSERT INTO scores (player_name, score) VALUES (?, ?)");
        $stmt->bind_param("si", $playerName, $score);
        $stmt->execute();
        $stmt->close();

        // Update session score
        $_SESSION['score'] += $score;
    }
}

// Function to display the leaderboard
function displayLeaderboard($conn) {
    $sql = "SELECT player_name, SUM(score) AS total_score FROM scores GROUP BY player_name ORDER BY total_score DESC LIMIT 10";
    $result = mysqli_query($conn, $sql);
    
    echo "<h2>Leaderboard</h2>";
    echo "<table class='table table-striped'>";
    echo "<tr><th>Player Name</th><th>Score</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>{$row['player_name']}</td><td>{$row['total_score']}</td></tr>";
    }
    echo "</table>";
}

// Display the leaderboard
displayLeaderboard($conn);

// Logout feature
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: game.php");
    exit();
}

// Clear old game data after specified time (e.g., 30 days)
function clearOldGameData($conn) {
    $stmt = $conn->prepare("DELETE FROM scores WHERE game_date < NOW() - INTERVAL 30 DAY");
    $stmt->execute();
    $stmt->close();
}

// Call the function to clear old data
clearOldGameData($conn);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Feud Game</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="text-center">Family Feud Game</h1>

        <!-- Player Name Form -->
        <form method="POST" action="game.php" class="mt-4">
            <div class="mb-3">
                <label for="player_name" class="form-label">Player Name:</label>
                <input type="text" name="player_name" id="player_name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Join Game</button>
        </form>

        <!-- Answer Form -->
        <form method="POST" action="game.php" class="mt-4">
            <div class="mb-3">
                <label for="answer" class="form-label">Your Answer:</label>
                <input type="text" name="answer" id="answer" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit Answer</button>
        </form>

        <!-- Leaderboard -->
        <?php displayLeaderboard($conn); ?>
        
        <!-- Logout Button -->
        <a href="game.php?logout=true" class="btn btn-danger mt-4">Logout</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
