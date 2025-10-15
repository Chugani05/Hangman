<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Repository\WordRepositoryInterface as WordRepositoryInterface;

final class WordRepository implements WordRepositoryInterface {
    
    public function __construct(private string $file) {}

    public function getRandomWord(): string {
        $words = file_get_contents($this->file);
        $list = json_decode($words, true);
        $randomIndex = array_rand($list['words']);
        $randomWord = $list['words'][$randomIndex];
        $result = iconv('utf-8', 'ASCII//TRANSLIT', $randomWord);
        return strtoupper(trim($result));
    }    
}