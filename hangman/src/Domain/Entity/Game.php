<?php
declare(strict_types=1);

namespace App\Domain\Entity;

final class Game {

    private $id;
    private string $word;
    private int $maxAttempts;
    private int $remainingAttempts;
    private array $guesses;


    public function __construct(string $word, int $maxAttempts, int $remainingAttempts, array $guesses = []) {
        $this->id = null;
        $this->word = $word;
        $this->maxAttempts = $maxAttempts;
        $this->remainingAttempts = $remainingAttempts;
        $this->guesses = $guesses;
    }

    public function guessLetter($letter) {
        $letter = strtoupper($letter);

        str_contains($this->getWord(), $letter);
    }

    public function getMaskedWord() {
        $maskedWord = "";

        foreach (str_split($this->getWord()) as $letra) {
            $maskedWord .= in_array($letra, $this->getGuesses()) ? $letra : "_";
        }
        return $maskedWord;
    }

    public function getRemainingAttempts() {
        return $this->remainingAttempts;
    }

    public function getMaxAttempts() {
        return $this->maxAttempts;
    }

    public function getGuesses() {
        return $this->guesses;
    }

    public function isWon(): bool {
        if ($this->getMaskedWord() == $this->getWord()) {
            return true;
        }
        return false;
    }

    public function isLost(): bool {
        if ($this->maxAttempts == 0) {
            return true;
        }
        return false;
    }

    public function getWord() {
        return $this->word;
    }

    public function getId() {
        return $this->id;
    }

    public function toArray(): array {
        return [
            "word" => $this->getWord(),
            "max_attempts" => $this->getMaxAttempts(),
            "remaining_attempts" => $this->getRemainingAttempts(),
            "guesses" => $this->getGuesses()
        ];
    }

    public static function fromArray(array $gameData): Game {
        return new Game(
            $gameData["word"],
            $gameData["max_attempts"],
            $gameData["remaining_attempts"],
            $gameData["guesses"]
        );
    }

}