<?php
declare(strict_types=1);

require __DIR__ . '/../src/Infrastructure/Autoload/Autoloader.php';
\App\Infrastructure\Autoload\Autoloader::register('App\\', __DIR__ . '/../src');

$config = require __DIR__ . '/../config/config.php';

use \App\Infrastructure\Persistence\WordRepository as WordRepository;

$wordRepository = new WordRepository($config['storage']['words_file']);
$word = $wordRepository->getRandomWord();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hangman with PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Hangman Game</h1>
</body>

<!--
<?php #echo Renderer::ascii($game->getAttemptsLeft()); ?>

<p>Words: <?php #echo implode(" ", str_split($maskedWord)); ?></p>
<p>Remaining attempts: <?php #echo $game->getAttemptsLeft(); ?></p>
<p>Used letters: <?php #echo implode(", ", $game->getUsedLetters()); ?></p>

<?php #if ($mensaje == ""): ?>
    <form id="keyboardForm" method="post">
        <div class="keyboard">
            <?php #foreach (range('A', 'Z') as $letra): ?>
                <button type="submit" class="key" name="letra" value="<?php #echo $letra; ?>"><?php #echo $letra; ?></button>
            <?php #endforeach; ?>
        </div>
    </form>

    <script>
        const usedLetters = <?php #echo json_encode($game->getUsedLetters()); ?>;
        const correctLetters = <?php #echo json_encode(array_intersect($game->getUsedLetters(), str_split($game->getWord()))); ?>;
        
        document.querySelectorAll('.key').forEach(key => {
            const letter = key.value;
            if (usedLetters.includes(letter)) {
                if (correctLetters.includes(letter)) {
                    key.classList.add('correct');
                } else {
                    key.classList.add('incorrect');
                }
                key.disabled = true;
            }
        });
    </script>

<?php #else: ?>
    <p><strong><?php #echo $mensaje; ?></strong></p>
    <a href="reset.php" class="play-again">Play again</a>
<?php #endif; ?>

</body>
</html> -->
