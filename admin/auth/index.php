<?php
session_start();
require __DIR__ . '/../../app/db.php';
$db = new db();
$config = $db->getConfig();
$user = $db->getUser();


if($_POST['username']){
    if (md5($_POST['captcha']) == $_SESSION['randomnr2']) {
    if($user['username'] == $_POST['username'] && $user['password'] == md5($_POST['password'])){

        $token = rand (100000000000000000,999999999999999999);
        if($db->insertToken($token)){
            setcookie("_t", $token, time()+172800, '/', $_SERVER['HTTP_HOST']);
            header("Location: http://{$_SERVER['HTTP_HOST']}/admin");
            exit;
        }else{
            $err = 'Ошибка записи в базу данных';
        }
    }else $err='Неверный логин или пароль';
}else{
        $err = 'Неверная капча';
    }
}

?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../../js/jquery.js"></script>
<!--    <script src="../js/js.js"></script>-->
</head>
<body>

<?php
if($err){
    echo '<div class="alert" style="display: block!important;">'. $err .'</div>';
}  ?>

<div class="content" style="height: 265px" >
    <form class="url-form" action="index.php" method="post"  >
        <input class="url-input" type="text" required id="url" name="username" placeholder="Логин">
        <input class="url-input" type="text" required id="url" name="password" placeholder="Пароль">
        <button type="submit" id="submit" name="submit" style="height:124px; position: relative; top:-62px;">Войти</button>

        <div class="captcha" style="position: relative; top:-62px;">
            <input class="url-input" type="text" name="captcha" id="captcha" placeholder="Капча" required >
            <div class="captcha-img">
                <img src="../../app/captcha.php" id="captcha-img" onclick="this.src='app/captcha.php?id=' + (+new Date());" >
            </div>
        </div>
    </form>
</div>

</body>
</html>




<footer>
    <?php
    if ($config['admin_canvas']==1){
        echo<<<HTML
        <!-- Canvas -->
        <div class="demo-1">
            <div id="large-header">
                <canvas id="demo-canvas">
                </canvas>
            </div>
        </div>
        <!-- Canvas end -->
        <script src="../../js/canvas/TweenLite.min.js"></script>
        <script src="../../js/canvas/EasePack.min.js"></script>
        <script src="../../js/canvas/demo-1.js"></script>
HTML;
    }
    ?>
</footer>
