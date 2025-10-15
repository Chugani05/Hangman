<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Game;

interface GameRepositoryInterface {
    public function get(string $gameId): ?Game;

    public function save(Game $game): string;
}

?>