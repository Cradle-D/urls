<?php

require __DIR__ . '/db.php';
session_start();

class Validator
{

    //Функция проверки Csrf-токена в сессиии и отправленного из формы.
    public function checkCsrfToken()
    {
        if (!empty($_POST['token'])) {
            if (hash_equals($_SESSION['token'], $_POST['token'])) {
                return true;
            } else {
                echo "csrf";
                return false;
            }
        }
        return false;
    }

    //Функция, проверяющаа правильность ввода капчи и дополнительно проверяет пришедшие поля из формы
    public function validateForm()
    {

        if (!preg_match('/^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9]\.[^\s]{2,})+$/u', $_POST['url']) && !preg_match('/^(http://'.$_SERVER['HTTP_HOST'].'/[a-zA-Z0-9]+)$/',$_POST['url'])) {
            echo "url";
            return false;
        }else{
            return true;
        }

    }

    public function validateCaptcha(){
        if (md5($_POST['captcha']) == $_SESSION['randomnr2']) {
            return true;
        }else{
            echo "captcha";
            return false;
        }
    }

    public function generateRandomSuffix($desired_length){
        $charset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand_str = '';
        while(strlen($rand_str) < $desired_length) $rand_str .= substr(str_shuffle($charset), 0, 1);
        return $rand_str;
    }

}


$val = new Validator();
$db = new db;
$config = $db->getConfig();

if($_POST['delete_url']){
    if($db->deleteUrl($_POST['delete_url'])){
        echo 'success_del';
    }else{
        echo 'error_del';
    }
}


if ($config['csrf']==1){
    if(!$val->checkCsrfToken()){
        die();
    }
}
if ($config['captcha']==1){
    if(!$val->validateCaptcha()){
        die();
    }
}

if($val->validateForm()){
    $suffix = $val->generateRandomSuffix($config['desired_length']);
    if ($db->insertIntoDb($_POST['url'],$suffix)){

        $date = date_create();
        echo 'succes '. $suffix.' '.date_timestamp_get($date);

    }else{
        echo 'db';
    }


}


