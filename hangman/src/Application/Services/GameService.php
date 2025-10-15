<?php 
declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Entity\Game as Game;
use App\Infrastructure\Persistence\GameRepository as GameRepository;
use App\Infrastructure\Persistence\SessionRepository as SessionRepository;
use App\Infrastructure\Persistence\WordRepository as WordRepository;

final class GameService {
    private $config;
    private $wordRepository;
    private $sessionRepository;
    private $gameRepository;

    public function __construct($config) {
        $this->config = $config;
        $this->wordRepository = new WordRepository($config['storage']['words_file']);
        $this->sessionRepository = new SessionRepository();
        $this->gameRepository = new GameRepository($config['storage']['games_file']);
    }

    public function handle() {
        // 1. Comprobar si es partida nueva o continuacion de la anterior
        $gameId = $this->sessionRepository->get("game_id");
        if ($gameId) {
            $game = new Game();
        } else {
            $word = $this->wordRepository->getRandomWord();
            $maxAttempts = $this->config['game']['max_attempts'];
            $game = new Game($word, $maxAttempts, $maxAttempts);
        }
    }
}

?>