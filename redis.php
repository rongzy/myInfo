<?php
ini_set("display_errors",1);
error_reporting(E_ALL);
    $redis = new Redis();
    $redis->connect('r-wz90d9ba4142c984.redis.rds.aliyuncs.com', 6379);
	$redis->auth("aslkdhLKAJAUdh25251"); 
echo '<pre>';
$redis->delete($redis->keys('*'));
print_r($redis->keys('*'));