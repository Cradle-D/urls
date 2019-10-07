<?php
require __DIR__ . '/app/db.php';
$db = new db();
$config = $db->getConfig();

if($config['csrf']==1){
session_start();
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['token'];
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery.js"></script>
    <script src="js/core.js"></script>
</head>
<body>

    <div class="alert hidden"></div>
    <div id="message" class="alert hidden"></div>
<div class="content">

    <form class="url-form" method="post" novalidate="novalidate" onsubmit="return false;" <?php if ($config['captcha']==1)echo 'style="height:150px;"' ?> >
        <input class="url-input" type="text" required id="url" name="url" placeholder="http://ваша ссылка">
        <button type="submit" id="submit" name="submit">Сократить</button>
        <?php if ($config['captcha']==1){
            echo<<<HTML
            <div class="captcha">
            <input class="url-input" type="text" name="captcha" id="captcha" placeholder="Капча" >
            <div class="captcha-img">
                <img src="app/captcha.php" id="captcha-img" onclick="this.src='app/captcha.php?id=' + (+new Date());" >
            </div>
        </div>
HTML;
        }else{
            echo '<input class="url-input" type="hidden" name="captcha" value="any" id="captcha"   >';
        }
         ?>

        <input type="hidden" name="token" id="token" value="<?php echo $token ?>" />

    </form>


    <textarea class="js-cuttextarea hidden-1px hidden">X</textarea>
    <br>
    <div class="recently-shorts" id="recently-shorts">


        <?php

        $reverseCookie = array_reverse($_COOKIE, false);
        $i=0;
        foreach ($reverseCookie as $key => $suffix){
            if (preg_match('/[0-9]+/', $key)){
                if($i<=7){
                echo '<div class="link"><div class="full-url">'. $db->getUrlBySuffix($suffix) . '</div>';
                echo '<div class="short-url"  > http://'.$_SERVER['HTTP_HOST'].'/' .$suffix. '</div>'; //onclick="copyToClipboard(this)"
                echo '<div class="delete-link"></div></div>';
                $i++;
                }
            }
        }
        ?>

    </div>



</div>


<footer>
    <?php
        echo '<script> var csrfActive ='.$config['csrf'].'; </script>';
        echo '<script> var captchaActive = '.$config['captcha'].';</script>';

    if ($config['canvas']==1){
        echo<<<HTML
    <!-- Canvas -->
    <div class="demo-1">
        <div id="large-header">
            <canvas id="demo-canvas">
            </canvas>
        </div>
    </div>
    <!-- Canvas end -->
    <script src="js/canvas/TweenLite.min.js"></script>
    <script src="js/canvas/EasePack.min.js"></script>
    <script src="js/canvas/demo-1.js"></script>
HTML;
    }
    ?>
</footer>
</body>
</html>

