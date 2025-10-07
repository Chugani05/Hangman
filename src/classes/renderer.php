<?php
namespace classes;

 /**
 * Clase Renderer
 * Esta clase proporciona métodos para renderizar diferentes representaciones
 * visuales del estado del juego
 * @author chugani05
 */
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
?>