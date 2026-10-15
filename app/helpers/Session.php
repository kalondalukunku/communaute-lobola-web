<?php
class Session {
    public static function start()
    {
        if (session_status() !== PHP_SESSION_NONE) {
            return;
        }

        $cookieParams = session_get_cookie_params();
        $cookieParams['path'] = '/';
        $cookieParams['httponly'] = true;
        $cookieParams['samesite'] = 'Lax';
        $cookieParams['secure'] = !empty($cookieParams['secure']) || (
            !empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off'
        ) || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);
        session_set_cookie_params($cookieParams);

        if (!session_start()) {
            throw new RuntimeException('Impossible de démarrer la session.');
        }
    }

    public static function set($key, $value)
    {
        self::start();

        if (!is_array($value) && !is_object($value)) {
            throw new InvalidArgumentException('Les données de session doivent être un tableau ou un objet.');
        }

        foreach ($value as $index => $item) {
            $_SESSION[strtolower(SITE_NAME_SESSION .'_'. $key)][$index] = $item;
            if($index === 'pswd') unset($_SESSION[strtolower(SITE_NAME_SESSION .'_'. $key)][$index]);
        }
    }

    public static function get($key)
    {
        self::start();
        $key = strtolower(SITE_NAME_SESSION .'_'. $key);
        return $_SESSION[$key] ?? null;
    }

    public static function destroy()
    {
        self::start();
        $_SESSION = [];

        if (!session_regenerate_id(true)) {
            throw new RuntimeException('Impossible de renouveler l’identifiant de session.');
        }
    }

    public static function setFlash($key, $message) 
    {
        self::start();
        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash($key) 
    {
        self::start();
        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            return $msg;
        }
        return null;
    }

    public static function hasFlash($key)
    {
        self::start();
        return !empty($_SESSION['flash'][$key]);
    }

    public static function isLogged($key = 'admin')
    {
        self::start();
        $key = strtolower(SITE_NAME_SESSION .'_'. $key);
        return isset($_SESSION[$key]) && !empty($_SESSION[$key]);
    }

}