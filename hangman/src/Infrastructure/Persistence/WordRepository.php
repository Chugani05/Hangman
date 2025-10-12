<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Repository\WordRepositoryInterface;

final class WordRepository implements WordRepositoryInterface {
    
    public function __construct(private string $file) {}

    public function getRandomWord(): string {
        $text = file_get_contents($this->file);
        $list = json_decode($text, true);
        $index = array_rand($list['words']);
        return $list['words'][$index];
    }    
}