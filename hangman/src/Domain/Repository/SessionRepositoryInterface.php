<?php
declare(strict_types=1);

namespace App\Domain\Repository;

interface SessionRepositoryInterface {

    public function get(string $key, $default = null): mixed;

    public function set(string $key, mixed $value): void;

    public function reset();

    public function destroy(): void;
}