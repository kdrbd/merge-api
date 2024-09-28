<?php

namespace Core;

define("DB_HOST", "localhost");
define("DB_NAME", "mergeapi");
define("DB_USER", "root");
define("DB_PASS", "tK123");

class Database
{
    private $dbhost = DB_HOST;
    private $dbname = DB_NAME;
    private $dbuser = DB_USER;
    private $dbpass = DB_PASS;
    public  $pdo;

    public function __construct()
    {
        if (!isset($this->pdo)) {
            try {
                $dbc = new \PDO("mysql:host=" . $this->dbhost . "; dbname=" . $this->dbname, $this->dbuser, $this->dbpass);
                $dbc->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $dbc->exec("SET CHARACTER SET utf8");
                $this->pdo = $dbc;
            } catch (\PDOException $e) {
                die("Failed to connect with DB" . $e->getMessage());
            }
        }
    }
}