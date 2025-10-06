<?php
namespace classes;

class WordProvider {
    private $filePath;    

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
?>