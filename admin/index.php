<?php
require __DIR__ . '/../app/db.php';
$db = new db();
$user = $db->getUser();


if ($_COOKIE['_t']!==$user['token']){
    header("Location: http://{$_SERVER['HTTP_HOST']}/admin/auth");
    exit();
}
$config = $db->getConfig();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <link rel="stylesheet" href="css/style.css">
    <script src="../js/jquery.js"></script>
    <script src="js/js.js"></script>
</head>
<body>

    <div class="alert hidden"></div>
    <div id="message" class="alert hidden"></div>
    <textarea class="js-cuttextarea hidden-1px hidden">X</textarea>
<div class="content">



    <div class="settings">
        <div class="setting-block">
        <div class="setting-name">Капча:</div>
        <div class="toggle-button toggle-button--aava">
            <input id="captcha" type="checkbox" <?php if($config['captcha']==1)echo 'checked' ?>>
            <label for="captcha" data-on-text="Вкл" data-off-text="Выкл"></label>
            <div class="toggle-button__icon"></div>
        </div>
        </div>

        <div class="setting-block">
        <div class="setting-name">CSRF токен:</div>
        <div class="toggle-button toggle-button--aava">
            <input id="csrf" type="checkbox" <?php if($config['csrf']==1)echo 'checked' ?>>
            <label for="csrf" data-on-text="Вкл" data-off-text="Выкл"></label>
            <div class="toggle-button__icon"></div>
        </div>
        </div>

        <div class="setting-block">
        <div class="setting-name">Canvas:</div>
        <div class="toggle-button toggle-button--aava">
            <input id="canvas" type="checkbox"<?php if($config['canvas']==1)echo 'checked' ?>>
            <label for="canvas" data-on-text="Вкл" data-off-text="Выкл"></label>
            <div class="toggle-button__icon"></div>
        </div>
        </div>

        <div class="setting-block">
        <div class="setting-name">Admin Canvas:</div>
        <div class="toggle-button toggle-button--aava">
            <input id="admin_canvas" type="checkbox" <?php if($config['admin_canvas']==1)echo 'checked' ?>>
            <label for="admin_canvas" data-on-text="Вкл" data-off-text="Выкл"></label>
            <div class="toggle-button__icon"></div>
        </div>
        </div>
    </div>
    <p>Последние созданные ссылки:</p>



    <div class="recently-shorts" id="recently-shorts">

        <div class="link">
            <div class="date-time">Дата создания</div>
            <div class="ip-addr">Ip-адресс</div>
            <div class="full-url-demo">Полная ссылка</div>
            <div class="short-url-demo">Сокращённая</div>
        </div>

        <?php
        $url=$db->getUrls(25);
        for ($i = count($url)-1; $i >= 0; $i--) {
            echo '<div class="link"><div class="date-time">' . $url[$i]['add_datetime'] . '</div>';
            echo '<div class="ip-addr">' . $url[$i]['ip'] . '</div>';
            echo '<div class="full-url">' . $url[$i]['url'] . '</div>';
            echo '<div class="short-url"> http://' . $_SERVER['HTTP_HOST'] . '/' . $url[$i]['short_suffix'] . '</div>';
            echo '<div class="delete-link"></div></div>';
        }
        ?>

        <div class="link"><a href="<?php echo "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}list";?>" class="open-all">Показать все ссылки</a></div>
    </div>




</div>


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
        <script src="../js/canvas/TweenLite.min.js"></script>
        <script src="../js/canvas/EasePack.min.js"></script>
        <script src="../js/canvas/demo-1.js"></script>
HTML;
        }
        ?>
    </footer>
</body>
</html>