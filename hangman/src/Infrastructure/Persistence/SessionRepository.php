<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Repository\SessionRepositoryInterface;

final class SessionRepository implements SessionRepositoryInterface {
    public function get(string $key, $default = null): mixed {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }

    public function reset() {
        session_start();
        $this->destroy();
        header("Location: index.php");
    }

    public function destroy(): void {
        session_destroy();
    }
}