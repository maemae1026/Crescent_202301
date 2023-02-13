<?php

declare(strict_types=1);
require_once dirname(__FILE__) . '/DB.php';

class Admin extends DB
{
    /**
     * PDOインスタンスを生成
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * IDパスワードを受けて認証結果を真偽値で返す
     *
     * @param string|null $id
     * @param string|null $pass
     * @return boolean
     */
    public function login(?string $id = '', ?string $pass = ''): bool
    {
        if (empty($id) || empty($pass)) return false;
        try {
            $sql  = 'SELECT';
            $sql .= ' *';
            $sql .= ' FROM ' . $this->tblAdmin;
            $sql .= ' WHERE login_id=:id';
            $stmt = $this->pdoObj->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();
            if ($result && $id === $result['login_id'] && password_verify($pass, $result['login_pass'])) {
                session_regenerate_id(true);
                return true;
            }
            return false;
        } catch (PDOException $e) {
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            exit($e->getMessage());
        }
    }

    /**
     * IDパスワードを追加
     *
     * @param string|null $id
     * @param string|null $pass
     * @return void
     */
    public function add(?string $id, ?string $pass): void
    {
        try {
            $sql  = 'INSERT';
            $sql .= ' INTO ' . $this->tblAdmin;
            $sql .= ' (login_id, login_pass)';
            $sql .= ' VALUES (:id, :pass)';
            $stmt = $this->pdoObj->prepare($sql);
            $stmt->bindValue(':id',   $id,   PDO::PARAM_STR);
            $stmt->bindValue(':pass', password_hash($pass, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            exit($e->getMessage());
        }
    }

    /**
     * ユーザ全件を取得
     *
     * @return array
     */
    public function all(): array
    {
        try {
            $sql  = 'SELECT';
            $sql .= ' *';
            $sql .= ' FROM ' . $this->tblAdmin;
            return $this->pdoObj->query($sql)->fetchAll();
        } catch (PDOException $e) {
            header('Content-Type: text/plain; charset=UTF-8', true, 500);
            exit($e->getMessage());
        }
    }
}
