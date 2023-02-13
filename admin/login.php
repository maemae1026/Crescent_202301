<?php

declare(strict_types=1);

session_start();

require_once dirname(__FILE__) . '/../Models/Admins.php';
require_once dirname(__FILE__) . '/../util.inc.php';

$id   = '';
$pass = '';
$isValidated = false;
if (!empty($_POST)) {
    $id   = $_POST['id'];
    $pass = $_POST['pass'];
    $isValidated = true;

    if ($id === '' || preg_match('/^(\s|　)+$/u', $id)) {
        $idError = 'ログインIDを入力してください。';
        $isValidated = false;
    }

    if ($pass === '' || preg_match('/^(\s|　)+$/u', $pass)) {
        $passError = 'パスワードを入力してください。';
        $isValidated = false;
    }

    if ($isValidated === true && (new Admins())->login($id, $pass)) {
        session_regenerate_id(true);
        $_SESSION['admin_id']    = $id;
        $_SESSION['admin_login'] = true;

        header('Location: index.php');
        exit;
    } else {
        $loginError = 'ログインIDまたはパスワードに誤りがあります。';
    }
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ログイン | Crescent Shoes 管理</title>
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
            <h1>ログイン</h1>
            <?php if (isset($idError)) : ?><p class="error"><?= $idError ?></p><?php endif; ?>
            <?php if (isset($passError)) : ?><p class="error"><?= $passError ?></p><?php endif; ?>
            <?php if (isset($loginError)) : ?><p class="error"><?= $loginError ?></p><?php endif; ?>
            <form action="" method="post">
                <table id="loginbox">
                    <tr>
                        <th>ログインID</th>
                        <td><input type="text" name="id" value="<?=h($id)?>"></td>
                    </tr>
                    <tr>
                        <th>パスワード</th>
                        <td><input type="password" name="pass" value="<?=h($pass)?>"></td>
                    </tr>
                </table>
                <p><input type="submit" value="ログイン"></p>
            </form>
        </main>
        <footer>
            <p>&copy; Crescent Shoes All rights reserved.</p>
        </footer>
    </div>
</body>

</html>
