<?php
class WordProvider {
    private $filePath;    
    /**
     * Inicializa el proveedor con la ruta del archivo que contiene las palabras
     *
     * @param  string $filePath
     * @return void
     */
    public function __construct(string $filePath) {
        $this->filePath = $filePath;
    }
    
    /**
     * Genera una palabra aleatoria.
     *
     * @return string
     */
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
    
    /**
     * Inicializa el juego con una palabra y número máximo de intentos, o restaura un estado guardado.
     *
     * @param  string $word
     * @param  int $maxAttempts
     * @param  array $state
     * @return void
     */
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
    
    /**
     * Registra una letra adivinada y reduce los intentos si no está en la palabra.
     *
     * @param  string $letter
     * @return void
     */
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
    
    /**
     * Devuelve la palabra enmascarada con guiones bajos para las letras no adivinadas.
     *
     * @return string
     */
    public function getMaskedWord(): string {
        $maskedWord = "";

        foreach (str_split($this->getWord()) as $letra) {
            $maskedWord .= in_array($letra, $this->getUsedLetters()) ? $letra : "_";
        }
        return $maskedWord;
    }
    
    /**
     * Devuelve el número de intentos restantes.
     *
     * @return int
     */
    public function getAttemptsLeft(): int {
        return $this->attemptsLeft;
    }
    
    /**
     * Devuelve las letras ya utilizadas en el juego.
     *
     * @return array
     */
    public function getUsedLetters(): array {
        return $this->usedLetters;
    }
    
    /**
     * Comprueba si el jugador ha ganado el juego.
     *
     * @return bool
     */
    public function isWon(): bool {
        if ($this->getMaskedWord() == $this->getWord()) {
            return true;
        }
        return false;
    }
    
    /**
     * Comprueba si el jugador ha perdido el juego.
     *
     * @return bool
     */
    public function isLost(): bool {
        if ($this->getAttemptsLeft() == 0) {
            return true;
        }
        return false;
    }
    
    /**
     * Devuelve la palabra del juego.
     *
     * @return string
     */
    public function getWord(): string {
        return $this->word;
    }
    
    /**
     * Convierte el estado actual del juego en un array para almacenarlo.
     *
     * @return array
     */
    public function toState(): array {
        return ["word" => $this->getWord(), "max_attempts" => $this->maxAttempts, "attempts_left" => $this->getAttemptsLeft(), "used_letters" => $this->getUsedLetters()];
    }

}

class Storage {
    private $key;    
    /**
     * Inicializa el almacenamiento con una clave de sesión (por defecto 'ahorcado').
     *
     * @param  string $key
     * @return void
     */
    public function __construct(string $key = 'ahorcado') {
        $this->key = $key;
    }
    
    /**
     * Obtiene un valor almacenado en la sesión, o devuelve un valor por defecto.
     *
     * @param  string $name
     * @param  mixed $default
     * @return mixed
     */
    public function get(string $name, $default = null): mixed {
        return $_SESSION[$name] ?? $default;
    }
    
    /**
     * Guarda un valor en la sesión.
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function set(string $name, $value): void {
        $_SESSION[$name] = $value;
    }
    
    /**
     * Reinicia la sesión y redirige al inicio del juego.
     *
     * @return void
     */
    public function reset(): void {
        session_start();
        session_destroy();
        header("Location: index.php");
    }
}

class Renderer {    
    /**
     * Devuelve una representación ASCII del estado del ahorcado según los intentos restantes.
     *
     * @param  int $attemptsLeft
     * @return string
     */
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
