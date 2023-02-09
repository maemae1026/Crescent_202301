<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;

Dotenv::createImmutable(__DIR__ . '/../')->load();

class DB
{
    protected $pdoObj;
    protected $tblNews;
    protected $tblAdmin;

    /**
     * インスタンスを生成
     */
    public function __construct()
    {
        $this->tblNews  = $_SERVER['TBL_NEWS'];
        $this->tblAdmin = $_SERVER['TBL_ADMN'];

        try {
            $this->pdoObj = new PDO('mysql:host=' . $_SERVER['DB_HOST'] . ';dbname=' . $_SERVER['DB_NAME'] . '; charset=utf8', $_SERVER['DB_USER'], $_SERVER['DB_PASS'],
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            exit($e -> getMessage());
        }
    }
}
