<?php

declare(strict_types=1);

session_start();

if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true) {
    header('Location: index.php');
    exit;
}

require_once dirname(__FILE__) . '/../Models/Admin.php';
require_once dirname(__FILE__) . '/../util.inc.php';

$id   = '';
$pass = '';
$isValidated = false;
if (!empty($_POST)) {
    $id   = $_POST['id'];
    $pass = $_POST['pass'];
    $isValidated = true;

    if ($id === '' || preg_match('/^(\s|　)+$/u', $id)) {
        $idError = '※ログインIDを入力してください。';
        $isValidated = false;
    }

    if ($pass === '' || preg_match('/^(\s|　)+$/u', $pass)) {
        $passError = '※パスワードを入力してください。';
        $isValidated = false;
    }

    if ($isValidated === true && (new Admin())->login($id, $pass)) {
        session_regenerate_id(true);
        $_SESSION['login_id']      = $id;
        $_SESSION['authenticated'] = true;

        header('Location: index.php');
        exit;
    } else {
        $loginError = '※ログインIDまたはパスワードに誤りがあります。';
    }
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ログイン | Crescent Shoes 管理</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <header class="pt-3 pb-4 mb-3">
        <div class="inner">
            <span><a href="index.php">Crescent Shoes 管理</a></span>
        </div>
    </header>
    <div id="container">
        <main>
            <form action="" method="post" class="form-signin d-b mt-5 mx-auto" style="width:400px" novalidate>
                <div class="card-deck text-center">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h4 class="my-0 font-weight-normal">
                                <img src="../images/logo01.png" width="100">
                            </h4>
                        </div>
                        <div class="card-body">
                            <?php if (isset($loginError)) : ?><p class="error alert alert-danger"><?= $loginError ?></p><?php endif; ?>
                            <?php if (isset($idError)) : ?><p class="error alert alert-danger"><?= $idError ?></p><?php endif; ?>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">
                                        ログインID
                                    </label>
                                </div>
                                <input type="text" name="id" value="<?= h($id) ?>" class="form-control" autofocus>
                            </div>
                            <?php if (isset($passError)) : ?><p class="error alert alert-danger"><?= $passError ?></p><?php endif; ?>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text">
                                        パスワード
                                    </label>
                                </div>
                                <input type="password" name="pass" class="form-control" value="<?= h($pass) ?>" class="form-control" autofocus>
                            </div>
                            <div class="d-grid">
                                <input class="btn btn-lg btn-primary" type="submit" name="login" value="ログイン">
                            </div>
                            <div class="mt-3">※ログインID・パスワードの登録は<a href="asign.php">こちら</a></div>
                        </div>
                    </div>
                </div>
            </form>
        </main>
        <footer>
            <p class="text-center">&copy; Crescent Shoes All rights reserved.</p>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
