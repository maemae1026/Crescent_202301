<?php
/**
 * ログイン済みの判定処理
 *
 * @return void
 */
function authConfirm(): void
{
    if (!isset($_SESSION['authenticated']) && $_SESSION['authenticated'] != true) {
        header('Location: login.php');
        exit;
    }
}
