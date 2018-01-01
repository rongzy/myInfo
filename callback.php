<?php
include_once "../conf/config.php";
$stringdd = json_encode($_REQUEST);
 //$sql = "insert into ms_error_online(`order_id`,`text`) values('2','{$stringdd}')";
 //$db->query($sql);
//$sql = "select text from ms_error_online where id='1688'";
 //$res = $db->query($sql);
 //$data = mysqli_fetch_assoc($res);

// $list = json_decode($data['text'],true);

 //$_REQUEST = $list;

if($_REQUEST){

$data = array();
$data['version'] = $_REQUEST["version"];
// 通知时间
$data['agentId'] = $_REQUEST["agentId"];
// 支付金额(单位元，显示用)
$data['agentOrderId'] = $_REQUEST["agentOrderId"];
// 商户号
$data['jnetOrderId'] = $_REQUEST["jnetOrderId"];
// 商户参数，支付平台返回商户上传的参数，可以为空
$data['payAmt'] = $_REQUEST["payAmt"];
// 订单号
$data['payResult'] = $_REQUEST["payResult"];
$sign = $_REQUEST["sign"];

	function Sign($param) {
		$string = '';
		foreach((array)$param as $k => $value) {
			$string .= $value . '|';
		}
		return $string;
	}
	 $sql = "select `key`,`merchant`,`url` from ms_cate where payname = 'kftxpay' limit 1";
$res = $db->query($sql);
$lists_2 = mysqli_fetch_assoc($res);

$md5Key = $lists_2['key'];
$sign2 = md5(Sign($data).$md5Key);



if($sign2 == $sign){
        $sql = "select id from ms_online where orders_id = '{$data['agentOrderId']}' limit 1";
    $result = $db->query($sql);
    $datas = mysqli_fetch_assoc($result);
   $sql = "update ms_online set opstate = '1' where id = '{$datas['id']}'";
  // $sql = "update ms_online set opstate = '1' where orders_id = '{$data['agentOrderId']}'";
   $res = $db->query($sql);
    $row =mysqli_affected_rows($db);
    if($row > 0){
         echo "ok";
     }
}else{
       $sql = "insert into ms_error_online(`order_id`,`text`) values('{$_REQUEST['agentOrderId']}','{$stringdd}')";
       $db->query($sql);
        echo "error";
    }

}else{
    header("Location:http://www.8887060.com");
}
