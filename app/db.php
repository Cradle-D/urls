<?php


class db
{
    public $mysqli;

    //Подключение к базе данных
    public function connectDb()
    {
        $this->mysqli = new mysqli("127.0.0.1", "root", "", "short_url", 3306);
        if ($this->mysqli->connect_errno) {
            echo "Не удалось подключиться к MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error;
            return false;
        }
        return true;
    }

    //Запись в базу данных
    public function insertIntoDb($url, $suffix){
        if ($this->connectDB()) {
            $url = $this->mysqli->real_escape_string($url);
            $ip = $this->mysqli->real_escape_string($_SERVER['REMOTE_ADDR']);
            $this->mysqli->query("INSERT INTO url (url, short_suffix, ip) VALUES ('$url', '$suffix' ,'$ip')");
            if($this->mysqli->sqlstate == 00000){
                return true;
            }else{
                return false;
            }
        }
    }

    public function getUrlBySuffix($suffix){
        $this->connectDB();
        $select = $this->mysqli->query("SELECT url FROM url WHERE BINARY short_suffix = '$suffix'");
        $url = $select->fetch_assoc();
        return $url['url'];
    }

    public function getConfig(){
        $this->connectDB();
        $select = $this->mysqli->query("SELECT * FROM settings");
        $select = $select->fetch_assoc();
        return $select;
    }

    public function deleteUrl($suffix){
        $this->connectDB();
        $select = $this->mysqli->query("SELECT ip FROM url WHERE BINARY short_suffix = '$suffix'");
        $url = $select->fetch_assoc();
        if ($url['ip']==$_SERVER['REMOTE_ADDR']){
            $this->mysqli->query("DELETE FROM url WHERE BINARY short_suffix = '$suffix'");
            if($this->mysqli->sqlstate == 00000){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function deleteUrlAdmin($suffix){
        $this->connectDB();
            $this->mysqli->query("DELETE FROM url WHERE BINARY short_suffix = '$suffix'");
            if($this->mysqli->sqlstate == 00000){
                return true;
            }else{
                return false;
            }
    }

    public function editSettings($setting, $value){
        $this->connectDB();
        $value = $this->mysqli->real_escape_string($value);
        $this->mysqli->query("UPDATE settings SET ".$setting."=".$value." WHERE id=1");
        if($this->mysqli->sqlstate == 00000){
            return true;
        }else{
            return false;
        }
    }

    public function getUrls($count){
        $this->connectDB();
        if ($count=='all'){
            $select = $this->mysqli->query("SELECT * FROM url");
        }else{
            $select = $this->mysqli->query("SELECT * FROM url LIMIT ".$count);
        }
        $urlArr=[];
        $i=0;
        while ($url = $select->fetch_assoc()){
            $urlArr[$i]=$url;
            $i++;
        }
        return $urlArr;
    }

    public function getUser(){
        $this->connectDB();
        $select = $this->mysqli->query("SELECT * FROM users WHERE id=1");
        $user = $select->fetch_assoc();
        return $user;
    }

    public function insertToken($token){
        $this->connectDB();
        $token = $this->mysqli->real_escape_string($token);
        $this->mysqli->query("UPDATE users SET token=".$token );
        if($this->mysqli->sqlstate == 00000){
            return true;
        }else{
            return false;
        }
    }



}