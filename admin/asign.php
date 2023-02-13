<?php

declare(strict_types=1);

session_start();

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true) {
    header('Location: index.php');
    exit;
}

require_once dirname(__FILE__) . '/../Models/Admin.php';
require_once dirname(__FILE__) . '/../util.inc.php';

$id     = '';
$pass   = '';
$retype = '';
$pdo = new Admin();
$isValidated = false;

if (!empty($_POST)) {
    $id     = $_POST['id'];
    $pass   = $_POST['pass'];
    $retype = $_POST['retype'];
    $isValidated = true;

    if ($id === '' || preg_match('/^(\s|　)+$/u', $id)) {
        $idError = '※ログインIDを入力してください。';
        $isValidated = false;
    }

    if ($pass === '' || preg_match('/^(\s|　)+$/u', $pass)) {
        $passError = '※パスワードを入力してください。';
        $isValidated = false;
    } elseif (!preg_match('/^(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}$/i', $pass)) {
        $passError = '※パスワードは半角英数字各1字以上を含む8〜100字以内で入力してください';
        $isValidated = false;
    }

    if ($retype === '' || mb_ereg_match('^(\s|　)+$', $retype)) {
        $retypeError = '※パスワードを再入力してください';
        $isValidated = false;
    } elseif ($pass !== $retype) {
        $retypeError = '※再入力のパスワードが一致しません';
        $isValidated = false;
    }

    if ($isValidated === true) {
        $pdo->add($id, $pass);

        $_SESSION['login_id']      = $id;
        $_SESSION['authenticated'] = true;

        header('Location: index.php');
        exit;
    } else {
        $loginError = 'ログインIDまたはパスワードに誤りがあります。';
    }
}
$admins = $pdo->all();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>アサイン | Crescent Shoes 管理</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <header>
        <div class="inner">
            <span><a href="index.php">Crescent Shoes 管理</a></span>
        </div>
    </header>
    <div id="container">
        <main>
            <h1>アサイン</h1>
            <?php if (isset($idError)) : ?><p class="error"><?= $idError ?></p><?php endif; ?>
            <?php if (isset($passError)) : ?><p class="error"><?= $passError ?></p><?php endif; ?>
            <?php if (isset($retypeError)) : ?><p class="error"><?= $retypeError ?></p><?php endif; ?>
            <form action="" method="post">
                <table id="loginbox">
                    <tr>
                        <th>ログインID</th>
                        <td><input type="text" name="id" value="<?= h($id) ?>"></td>
                    </tr>
                    <tr>
                        <th>パスワード</th>
                        <td><input type="password" name="pass" value="<?= h($pass) ?>"></td>
                    </tr>
                    <tr>
                        <th>再入力</th>
                        <td><input type="password" name="retype" value="<?= h($retype) ?>"></td>
                    </tr>
                </table>
                <p>
                    <input type="submit" value="登録">
                </p>
            </form>
            <table>
                <tr>
                    <th>番号</th>
                    <th>ログインID</th>
                    <th>パスワード</th>
                </tr>
                <?php foreach ($admins as $admin):?>
                    <tr>
                        <td><?=$admin['id']?></td>
                        <td><?=$admin['login_id']?></td>
                        <td><?=$admin['login_pass']?></td>
                    </tr>
                <?php endforeach;?>
            </table>
        </main>
        <footer>
            <p>&copy; Crescent Shoes All rights reserved.</p>
        </footer>
    </div>
</body>

</html>
