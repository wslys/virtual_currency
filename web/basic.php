<?php
/**
 * 用户数据/ 用户设备维护PHP 脚本
 * Created by PhpStorm
 * User: wsl
 * Date: 2017/12/21
 * Time: 10:38
 */
include "MysqlDB.php";

$db = new MysqlDB(array(
    "host"     => "127.0.0.1",
    "port"     => 3306,
    "username" => "root",
    "password" => "",
    "charset"  => "utf8",
    "dbname"   => "tutorial"
));

