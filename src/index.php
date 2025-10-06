<?php
include "./classes/game.php";
include "./classes/storage.php";
include "./classes/renderer.php";
include "./classes/wordProvider.php";

use classes\Game as Game;
use classes\Storage as Storage;
use classes\Renderer as Renderer;
use classes\WordProvider as WordProvider;

session_start();

$storage = new Storage();
$savedGame = $storage->get("saved_game");

$wordProvider = new WordProvider("./files/words.txt");
$game = new Game($wordProvider->randomWord(), state: $savedGame);

if (isset($_POST['letra'])) {
    $letra = strtoupper($_POST['letra']);
    $game->guessLetter($letra);
}

$maskedWord = $game->getMaskedWord();

$mensaje = "";
if ($game->isWon()) {
    $mensaje = "Felicidades ¡Ganaste! La palabra era: " . $game->getWord();
}
if ($game->isLost()) {
    $mensaje = "Lo siento ¡Perdiste! La palabra era: " . $game->getWord();
}

$storage->set("saved_game", $game->toState());
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hangman with PHP</title>
</head>
<body>
<h1>Hangman Game</h1>

<?php echo Renderer::ascii($game->getAttemptsLeft()); ?>

<p>Words: <?php echo implode(" ", str_split($maskedWord)); ?></p>
<p>Remaining attempts: <?php echo $game->getAttemptsLeft(); ?></p>
<p>Used letters: <?php echo implode(", ", $game->getUsedLetters()); ?></p>

<?php if ($mensaje == ""): ?>
    <form method="post">
        <label>Enter a letter:</label>
        <input type="text" name="letra" maxlength="1" required>
        <button type="submit">Guess</button>
    </form>
<?php else: ?>
    <p><strong><?php echo $mensaje; ?></strong></p>
    <a href="reset.php">Play again</a>
<?php endif; ?>

</body>
</html>
