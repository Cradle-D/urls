<?php
require __DIR__ . '/db.php';
$suffix = $_SERVER['REQUEST_URI'];
$suffix = substr($suffix, 1);

$db = new db();
$url = $db->getUrlBySuffix($suffix);
if ($url==Null){
    header('Location: ' . '404.html', true,301);
    die();
}else{
    header('Location: ' . $url, true,301);
    die();
}
