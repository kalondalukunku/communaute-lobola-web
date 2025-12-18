<?php
class Session {
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) session_start();
    }

    public static function set($key, $value)
    {
        foreach ($value as $index => $item) {
            $_SESSION[strtolower(SITE_NAME_SESSION .'_'. $key)][$index] = $item;
            if($index === 'pswd') unset($_SESSION[strtolower(SITE_NAME_SESSION .'_'. $key)][$index]);
        }
    }

    public static function get($key)
    {
        $key = strtolower(SITE_NAME_SESSION .'_'. $key);
        return $_SESSION[$key] ?? null;
    }

    public static function destroy()
    {
        $_SESSION = [];

        if(ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(),"", time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }

        session_start();
    }

    public static function setFlash($key, $message) 
    {
        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash($key) 
    {
        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            return $msg;
        }
        return null;
    }

    public static function hasFlash($key)
    {
        return !empty($_SESSION['flash'][$key]);
    }

    public static function isLogged($key = 'admin')
    {
        self::start();
        $key = strtolower(SITE_NAME_SESSION .'_'. $key);
        return isset($_SESSION[$key]) && !empty($_SESSION[$key]);
    }

}