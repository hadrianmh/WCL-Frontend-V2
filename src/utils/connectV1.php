<?php
error_reporting(0);
date_default_timezone_set("Asia/jakarta");

// LOAD CONFIG
$configfile = '../config.json';
if (file_exists($configfile)) {
    $getconfig = file_get_contents($configfile);
    $ENV = json_decode($getconfig, TRUE);
    if($ENV !== null && $ENV['database_host'] !== null && $ENV['database_user'] !== null && $ENV['database_pass'] !== null && $ENV['database_name'] !== null) {
        $host = $ENV['database_host'];
        $user = $ENV['database_user'];
        $pass = $ENV['database_pass'];
        $dbase = $ENV['database_name'];
        $connect = new mysqli($host,$user,$pass,$dbase);

        // print_r($connect);

        if($connect->connect_error){
            echo 'Connection failed: server offline';
            exit();
        }

    } else {
        echo "Config is not configured well.";
        exit();
    }
}else {
    echo "Config file not found.";
    exit();
}
?>