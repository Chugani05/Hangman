<?php
namespace classes;

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
?>