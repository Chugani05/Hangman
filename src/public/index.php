<?php
class WordProvider {
    private $filePath;
    public function __construct(string $filePath) {
        $this->filePath = $filePath;
    }

    public function randomWord(): string {
        $words = file( $this->filePath);
        $randomIndex = array_rand($words,1);
        $randomWord = $words[$randomIndex];
        $result = iconv('utf-8', 'ASCII//TRANSLIT', $randomWord);
        return strtoupper(trim($result));
    }
}

class Game {
    private $word;
    private $maxAttempts;
    private $attemptsLeft;
    private $usedLetters;

    public function __construct(string $word, int $maxAttempts = 6, ?array $state = null) {
        if ($state !== null) {
           $this->word = $state["word"];
           $this->maxAttempts = $state["max_attempts"];
           $this->attemptsLeft = $state["attempts_left"];
           $this->usedLetters = $state["used_letters"];
        } else {
            $this->word = $word;
            $this->maxAttempts = $maxAttempts;
            $this->attemptsLeft = $maxAttempts;
            $this->usedLetters = [];
        }
    }

    public function guessLetter(string $letter): void {
        $letter = strtoupper($letter);

        if (in_array($letter, $this->usedLetters)) {
            return;
        }
        if (!str_contains($this->getWord(), $letter)) {
            $this->attemptsLeft--;
        }

        $this->usedLetters[] = $letter;
    }

    public function getMaskedWord(): string {
        $maskedWord = "";

        foreach (str_split($this->getWord()) as $letra) {
            $maskedWord .= in_array($letra, $this->getUsedLetters()) ? $letra : "_";
        }
        return $maskedWord;
    }

    public function getAttemptsLeft(): int {
        return $this->attemptsLeft;
    }

    public function getUsedLetters(): array {
        return $this->usedLetters;
    }

    public function isWon(): bool {
        if ($this->getMaskedWord() == $this->getWord()) {
            return true;
        }
        return false;
    }

    public function isLost(): bool {
        if ($this->getAttemptsLeft() == 0) {
            return true;
        }
        return false;
    }

    public function getWord(): string {
        return $this->word;
    }

    public function toState(): array {
        return ["word" => $this->getWord(), "max_attempts" => $this->maxAttempts, "attempts_left" => $this->getAttemptsLeft(), "used_letters" => $this->getUsedLetters()];
    }

}

class Storage {
    private $key;
    public function __construct(string $key = 'ahorcado') {
        $this->key = $key;
    }

    public function get(string $name, $default = null) {
        return $_SESSION[$name] ?? $default;
    }

    public function set(string $name, $value): void {
        $_SESSION[$name] = $value;
    }

    public function reset(): void {
        session_start();
        session_destroy();
        header("Location: index.php");
    }
}

class Renderer {
    static public function ascii(int $attemptsLeft): string {
    $state = [
        6 => " 
  +---+
  |   |
      |
      |
      |
      |
========= ",
        5 => " 
  +---+
  |   |
  O   |
      |
      |
      |
========= ",
        4 => " 
  +---+
  |   |
  O   |
  |   |
      |
      |
========= ",
        3 => " 
  +---+
  |   |
  O   |
 /|   |
      |
      |
========= ",
        2 => " 
  +---+
  |   |
  O   |
 /|\  |
      |
      |
========= ",
        1 => " 
  +---+
  |   |
  O   |
 /|\  |
 /    |
      |
========= ",
        0 => " 
  +---+
  |   |
  O   |
 /|\  |
 / \  |
      |
========= "
    ];
    return "<pre>" . $state[$attemptsLeft] . "</pre>";
}
}

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

<p>Palabra: <?php echo implode(" ", str_split($maskedWord)); ?></p>
<p>Intentos restantes: <?php echo $game->getAttemptsLeft(); ?></p>
<p>Letras usadas: <?php echo implode(", ", $game->getUsedLetters()); ?></p>

<?php if ($mensaje == ""): ?>
    <form method="post">
        <label>Introduce una letra:</label>
        <input type="text" name="letra" maxlength="1" required>
        <button type="submit">Adivinar</button>
    </form>
<?php else: ?>
    <p><strong><?php echo $mensaje; ?></strong></p>
    <a href="reset.php">Jugar de nuevo</a>
<?php endif; ?>

</body>
</html>
