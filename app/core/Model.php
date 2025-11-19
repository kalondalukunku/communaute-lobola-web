<?php
require_once APP_PATH . 'core/Database.php';
require_once APP_PATH . 'helpers/Cache.php';

class Model {
    public $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }
}