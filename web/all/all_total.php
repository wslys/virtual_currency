<?php
/**
 * Created by PhpStorm
 * User: wsl
 * Date: 2017/12/21
 * Time: 14:21
 */
$sql1 = "SELECT FORMAT(SUM(total_fee) / 100,2)  fee FROM orders where status=1"; // 总金额
$sql2 = "SELECT FORMAT(SUM(total_fee) / 100,2)  fee FROM orders where status=1 AND to_days(create_time) = to_days(now())"; // 今日: 收入

$result1 = $db->getRow($sql1);
$result2 = $db->getRow($sql2);

exit(json_encode(array(
    "total_amount" => isset($result1['fee'])?$result1['fee']:0,
    "today_amount" => isset($result2['fee'])?$result2['fee']:0,
    "user_id"      => $uid
)));


