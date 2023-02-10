<?php

declare(strict_types=1);
require_once dirname(__FILE__) . '/DB.php';

class News extends DB
{
    /**
     * PDOインスタンスを生成
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ニュースの全件を返す
     * 
     * @param string|null $desc
     * @param integer|null $startNum
     * @param integer $getNum
     * @return array
     */
    public function all(?string $desc = 'ASC', ?int $startNum = 0, $getNum = 0): array
    {
        try {
            $sql  = 'SELECT';
            $sql .= ' *';
            $sql .= ' FROM ' . $this->tblNews;
            if ($desc) {
                $sql .= ' ORDER BY posted_at DESC';
            }
            if ($getNum > 0) {
                $sql .= ' LIMIT ' . $startNum . ', ' .  $getNum;
            }
            return $this->pdoObj->query($sql)->fetchAll();
        } catch (PDOException $e) {
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            exit($e->getMessage());
        }
    }

    /**
     * 4つのフォームの値を元に1件のレコードを追加
     *
     * @param array|null $postArr
     * @return void
     */
    public function add(array $postArr): void
    {
        $sql  = 'INSERT';
        $sql .= ' INTO ' . $this->tblNews;
        $sql .= ' (posted_at, title, message, image)';
        $sql .= ' VALUES ("' . $postArr['posted'] . '", :title, :message, "' . $postArr['image'] . '")';
        $stmt = $this->pdoObj->prepare($sql);
        $stmt->bindValue(':title',   $postArr['title'],   PDO::PARAM_STR);
        $stmt->bindValue(':message', $postArr['message'], PDO::PARAM_STR);
        $stmt->execute();
    }
}
