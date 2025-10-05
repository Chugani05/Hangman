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

$wordProvider = new WordProvider("./files/words.txt");

if (!isset($_SESSION['palabra'])) {
    $_SESSION['palabra'] = $wordProvider->randomWord();
    $_SESSION['intentos'] = 6;
    $_SESSION['letras_usadas'] = [];
}

if (isset($_POST['letra'])) {
    $letra = strtoupper($_POST['letra']);
    if (!in_array($letra, $_SESSION['letras_usadas'])) {
        $_SESSION['letras_usadas'][] = $letra;
        if (strpos($_SESSION['palabra'], $letra) === false) {
            $_SESSION['intentos']--;
        }
    }
}

$mostrar = "";
foreach (str_split($_SESSION['palabra']) as $letra) {
    $mostrar .= in_array($letra, $_SESSION['letras_usadas']) ? $letra : "_";
}

$mensaje = "";
if ($mostrar === $_SESSION['palabra']) {
    $mensaje = "Felicidades ¡Ganaste! La palabra era: " . $_SESSION['palabra'];
}
if ($_SESSION['intentos'] <= 0) {
    $mensaje = "Lo siento ¡Perdiste! La palabra era: " . $_SESSION['palabra'];
}


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ahorcado en PHP</title>
</head>
<body>
<h1>Juego del Ahorcado</h1>

<?php echo Renderer::ascii($_SESSION['intentos']); ?>

<p>Palabra: <?php echo implode(" ", str_split($mostrar)); ?></p>
<p>Intentos restantes: <?php echo $_SESSION['intentos']; ?></p>
<p>Letras usadas: <?php echo implode(", ", $_SESSION['letras_usadas']); ?></p>

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
