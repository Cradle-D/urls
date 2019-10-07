<?php
require __DIR__ . '/../../app/db.php';
$db = new db();
$user = $db->getUser();

if ($_COOKIE['_t']!==$user['token']){
    header("Location: http://{$_SERVER['HTTP_HOST']}/admin/auth");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<link rel="stylesheet" href="../css/style.css">
<script src="../../js/jquery.js"></script>
<script src="../js/js.js"></script>
</head>
<body>

<div class="alert hidden"></div>
<div id="message" class="alert hidden"></div>
<textarea class="js-cuttextarea hidden-1px hidden">X</textarea>

<div class="recently-shorts" id="recently-shorts">
    <div class="link">
        <div class="date-time">Дата создания</div>
        <div class="ip-addr">Ip-адресс</div>
        <div class="full-url-demo">Полная ссылка</div>
        <div class="short-url-demo">Сокращённая</div>
    </div>
    <?php

    $url = $db->getUrls('all');
    for ($i = count($url)-1; $i >= 0; $i--) {
        echo '<div class="link"><div class="date-time">' . $url[$i]['add_datetime'] . '</div>';
        echo '<div class="ip-addr">' . $url[$i]['ip'] . '</div>';
        echo '<div class="full-url">' . $url[$i]['url'] . '</div>';
        echo '<div class="short-url"> http://' . $_SERVER['HTTP_HOST'] . '/' . $url[$i]['short_suffix'] . '</div>';
        echo '<div class="delete-link"></div></div>';
    }

    ?>

</div>
</body>
</html>