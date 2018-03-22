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
    "password" => "mysqlroot@kgdb.info",
    "charset"  => "utf8",
    "dbname"   => "pay",
));

$act = isset($_GET['act'])?$_GET['act']:'home';
$uid        = isset($_GET['uid'])?$_GET['uid']:1;
$view       = isset($_GET['view'])?$_GET['view']:'weeks_view';
$start_date = isset($_GET['start_date'])?$_GET['start_date']:'';
$end_date   = isset($_GET['end_date'])?$_GET['end_date']:'';

$actArr = array('all', 'all_total','all_hours');
if (in_array($act, $actArr)) {
    include $act . '.php';
}else {
    exit(json_encode(array(
        "code" => 100,
        "msg"  => "未提供此操作!"
    )));
}

