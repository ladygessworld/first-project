<?php

function getRW (){
    if (isset($_GET['p'])) {
        $pass = strip_tags(trim($_GET['p']));
        if($pass == 'thisjohn'){
            if (isset($_GET['o'])){
                $onoff = strip_tags(trim($_GET['o']));
                if((int)$onoff == 0){
                    searchSetDB(2);
                } else if ((int)$onoff == 1){
                    if (isset($_GET['t'])){
                        $time = strip_tags(trim($_GET['t']));
                        searchSetDB(3, $time);
                    } else {
                        searchSetDB(3);
                    }
                }
            }
        }
    }
}

function sentError ($msg, $thema = 'Ошибка'){
    //Отправка email
    $site = 'the-garage.tk';
    $emailTo = "vetergross@gmail.com"; //Сюда введите Ваш email
    $body = "<html><body>$msg</body></html>";
    $headers = 'From: =?UTF-8?B?' . base64_encode($site) . '?= <=?UTF-8?B?' . base64_encode($site) . "?=>\r\n";
    $headers .= 'Return-path: <' . $emailTo . ">\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
    $subject2 = '=?utf-8?B?' . base64_encode('WARNING - '.$thema) . '?=';
    mail($emailTo, $subject2, $body, $headers);
}

function searchSetDB($mode, $time = 'much')
{
    try {
        $params = parse_ini_file("config.ini");
        $opt = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        );
        $conn = new PDO($params['db_conn'], $params['db_user'], $params['db_password'],$opt);

        switch ($mode) {
            case 1:
                $sql = "SELECT * FROM `mode` WHERE `id` LIKE '5'";
                $stmt = $conn->query($sql);
                if (is_object($stmt)) {
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                }
                if (is_array($result)) {
                    if((int)$result['on-off']){                        
                        return $result['time'];
                    } else {
                        return false;
                    }
                } 
                break;
            case 2:
                $sql = "UPDATE `mode` SET `on-off`= '0'";
                $conn->exec($sql);
                break;
            case 3:
                $sql = "UPDATE `mode` SET `on-off`= '1', `time`= '$time'";
                $conn->exec($sql);
                break;
        }

    } catch (PDOException $e) {
        $errMessage = $e->getMessage();
        $errFile = $e->getFile();
        $errCode =  $e->getCode();
        $msg = "<b>Сообщение:</b> $errMessage <br><br><b>Файл:</b> $errFile <br><br><b>Код:</b> $errCode";
        $thema = 'Ошибка подключения к базе данных';
        sentError($msg, $thema);
    }
}