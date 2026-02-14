<?php
class Auth {
    public static function check()
    {
        Session::start();
        return Session::get('member_id') !== null;
    }

    public static function requireLogin($keys = ['admin'])
    {
        Session::start();

        // Si ce n'est pas un tableau → convertir
        if (!is_array($keys)) {
            $keys = [$keys];
        }

        $isLogged = false;

        // Vérifier si au moins une session existe
        foreach ($keys as $key) {
            if (Session::get($key)) {
                $isLogged = true;
                break;
            }
        }

        // Si aucune session trouvée
        if (!$isLogged) {

            Session::setFlash('error', 'Veuillez vous connecter pour continuer');

            // Redirection selon contexte
            if (in_array('admin', $keys)) {
                Utils::redirect('/admin/login');
            } else {
                Utils::redirect('/login');
            }

            exit;
        }
    }

    public static function admin() 
    {
        Session::start();
        return Session::get('admin');
    }
}