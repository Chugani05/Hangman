<?php
namespace classes;

/**
 * Clase Storage
 * Esta clase facilita la gestión del almacenamiento en la sesión
 * @author chugani05
 */
class Storage {
    private $key;    

    public function __construct(string $key = 'ahorcado') {
        $this->key = $key;
    }
    
    /**
     * Obtiene un valor almacenado en la sesión, o devuelve un valor por defecto.
     *
     * @param  string $name
     * @param  mixed $default
     * @return mixed
     */
    public function get(string $name, $default = null): mixed {
        return $_SESSION[$name] ?? $default;
    }
    
    /**
     * Guarda un valor en la sesión.
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function set(string $name, $value): void {
        $_SESSION[$name] = $value;
    }
    
    /**
     * Reinicia la sesión y redirige al inicio del juego.
     *
     * @return void
     */
    public function reset(): void {
        session_start();
        session_destroy();
        header("Location: index.php");
    }
}
?>