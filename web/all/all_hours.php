<?php
/**
 * Created by PhpStorm
 * User: wsl
 * Date: 2017/12/21
 * Time: 14:21
 */
//$sql = "SELECT DATE_FORMAT(create_time, '%Y-%m-%d') to_time, FORMAT(SUM(total_fee) / 100,2) fee  FROM orders WHERE DATE_SUB(CURDATE(), INTERVAL 7 DAY) < date(create_time) AND status=1 group by to_time";
//$sql = "SELECT HOUR(create_time) as to_time, FORMAT(SUM(total_fee) / 100,2) as fee  FROM orders WHERE to_days(create_time) = to_days(now()) AND status=1 group by HOUR(create_time)";
$sql = "SELECT DATE_FORMAT(create_time, '%Y-%m-%d:%H') as to_time, FORMAT(SUM(total_fee) / 100,2) as fee  FROM orders WHERE create_time >= (NOW() - interval 25 hour) AND status=1 group by DATE_FORMAT(create_time,'%Y-%m-%d:%H')";
$result = $db->getRows($sql);

if ($result != NULL) {
    exit(json_encode(array("code" => 0, "msg" => "success", "data" => $result)));
}else {
    exit(json_encode(array("code" => 100, "msg" => "error", "data" => $result)));
}

