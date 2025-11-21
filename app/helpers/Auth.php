<?php
class Auth {
    public static function check()
    {
        Session::start();
        return Session::get('user_id') !== null;
    }
    
    public static function requireLogin($key = 'admin') 
    {
        Session::start();
        if (!Session::get($key)) 
        {
            Session::setFlash('error', 'Veuillez vous connecter pour continuer');
            if ($key === 'admin') Utils::redirect('/admin/login');
            else Utils::redirect('/login');
            exit;
        }
    }

    public static function admin() 
    {
        Session::start();
        return Session::get('admin');
    }

    public static function isRole($role, $role2 = null)
    {
        if ($role2 !== null)
        {
            if($_SESSION[SITE_NAME_SESSION_USER]['role'] !== $role && $_SESSION[SITE_NAME_SESSION_USER]['role'] !== $role2) Utils::redirect('/');
        } 
        else {
            if($_SESSION[SITE_NAME_SESSION_USER]['role'] !== $role && $_SESSION[SITE_NAME_SESSION_USER]['role'] === ARRAY_ROLE_USER[0]) Utils::redirect('/');
            elseif($_SESSION[SITE_NAME_SESSION_USER]['role'] !== $role && $_SESSION[SITE_NAME_SESSION_USER]['role'] === ARRAY_ROLE_USER[1]) Utils::redirect('/supervisor');
            elseif($_SESSION[SITE_NAME_SESSION_USER]['role'] !== $role && $_SESSION[SITE_NAME_SESSION_USER]['role'] === ARRAY_ROLE_USER[2]) Utils::redirect('/courier/create');
        }
        
    }
}