<?php
/**
 * Created by PhpStorm
 * User: wsl
 * Date: 2017/12/21
 * Time: 14:21
 */
$format  = '%Y-%m-%d';

// $sql = "SELECT DATE_FORMAT(create_time, '$format') to_time, SUM(total_fee) fee FROM orders"; //TODO 全部
$sql = "SELECT DATE_FORMAT(create_time, '%Y-%m-%d') to_time, FORMAT(SUM(total_fee) / 100,2) fee  FROM orders WHERE DATE_SUB(CURDATE(), INTERVAL 7 DAY) < date(create_time) AND status=1 group by to_time";
$result = $db->getRows($sql);

if ($result != NULL) {
    exit(json_encode(array("code" => 0, "msg" => "success", "data" => $result)));
}else {
    exit(json_encode(array("code" => 100, "msg" => "error", "data" => $result)));
}

