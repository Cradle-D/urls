<?php
require __DIR__ . '/../app/db.php';
$db = new db();
if ($_POST['edit_setting']){
    if($db->editSettings($_POST['edit_setting'],$_POST['value'])){
        echo 'Настройка изменена';
    }else{
        echo 'Ошибка';
    }
}
if($_POST['delete_url']){
    if($db->deleteUrlAdmin($_POST['delete_url'])){
        echo 'success_del';
    }else{
        echo 'error_del';
    }
}

