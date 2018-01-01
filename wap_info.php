<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/info_wap.css" />
        <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js" ></script>
          <script type="text/javascript"src="../Js/qrcode.js"></script>
          <script type="text/javascript">
            function goback(){
                window.location.href="http://www.8887060.com";
            }

            (function(){
             var html=document.documentElement
             var width=html.getBoundingClientRect().width;
            // console.log(width) //手机尺寸
             html.style.fontSize=width/15+'px';
        })()

        $(function(){
            $('.sub-box').click(function(){
                $(this).css({'background':'url(../images/sub_1.png) no-repeat center center','background-color':'red'})
            })
        })
    </script>
        <title></title>
    </head>
    <body>

    <?php
    // 判断是否是扫码付款
    include("../conf/config.php");
    if(empty($_POST['name']) || empty($_POST['price'])){
        echo "
    <a href='http://www.8887060.com'><div class=\"ups\">
                <div class=\"ups-box\">
                </div>
            </div></a>
            <script>
                   setTimeout(\"window.location.href='http://www.8887060.com'\",2000);
            </script>
         ";exit();
    }
    if(empty($_POST['pay_type'])){
         echo "<script > alert('请选择支付方式');window.history.back();</script>";exit();
    }
    $_POST['price'] = (double) $_POST['price'];
    if($_POST['pay_way'] == "1"){
   $url = "http://47.92.69.227/gateway/payment";
	$sql = "select `merchant`,`url`,`key` from ms_cate where payname ='kftxpay' limit 1";
	$res = mysqli_query($db,$sql);
	$data = mysqli_fetch_array($res,MYSQLI_ASSOC);
	$list['version'] ='1.0'; //商户号
	$list['agentId'] =$data['merchant']; //商户号
	$list['agentOrderId'] = 'aakftxpayaa'.date("YmdHis").rand(000,999);
	switch ($_POST['pay_type']) {
		case 'wx':
			$list['payType'] = "20"; //支付类型21-微信，30-支付宝,31-QQ钱包
    		$cate_id = 82;
			break;
		//case 'ali':
		//	$list['payType'] = "40"; //支付类型21-微信，30-支付宝,31-QQ钱包
    	//	$cate_id = 81;
		//	break;
		case 'QQ':
			$list['payType'] = "70"; //支付类型21-微信，30-支付宝,31-QQ钱包
	 		$cate_id = 83;
			break;
	}

	$list['payAmt'] = $_POST['price'];//充值金额
	$list['orderTime'] = date("YmdHis");//充值金额
	$list['payIp'] = $_SERVER['REMOTE_ADDR'];
	$list['notifyUrl'] = $data['url']; //异步通知地址
	$md5Key = $data['key'];
/* 构建签名原文 */
	function Sign($param) {
		$string = '';
		foreach((array)$param as $k => $value) {
			$string .= $value . '|';
		}
		return $string;
	}

	$param = Sign($list);
	//var_dump($param);die;
	$list['sign'] = md5($param.$md5Key);
	
	
   
    $orders_id =   $list['agentOrderId'];
    $orders_user = $_POST['name'];
    $orders_money = $_POST['price'];
    $ssl_sign = json_encode($list);
    $client_ip = $_SERVER['REMOTE_ADDR'];
    $create_time = date("Y-m-d H:i:s");
    $sql = "insert into ms_online(orders_id,cate_id,orders_user,orders_money,ssl_sign,client_ip,create_time) values('{$orders_id}','{$cate_id}','{$orders_user}','{$orders_money}','{$ssl_sign}','{$client_ip}','{$create_time}')";
    mysqli_query($db,$sql);
    function buildForm($data, $gateway) {
            $sHtml = "<form id='paysubmit' name='bankPaySubmit' action='".$gateway."' method='post'>";
            while (list ($key, $val) = each ($data)) {
                $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
            }
            $sHtml.= "</form>";
            $sHtml.= "<script>document.forms['bankPaySubmit'].submit();</script>";
            return $sHtml;
    }
    echo buildForm($list,$url);
    exit();
        ?>
    <?php
    }elseif($_POST['pay_way'] == "2"){
        $orders_id = $_POST['name'].'online'.date("YmdHis");
        $price = $_POST['price'];
        $time = date("Y-m-d H:i:s");
        $pay_type = $_POST['pay_type'];

        switch ($_POST['pay_type']) {
            case 'wx':
                $id =13;
                $images ="wx.png";
                break;
            case 'ali':
                $id =12;
                $images ="ali.png";
                break;
            case 'QQ':
                $id =11;
                $images ="QQ.png";
                break;
            case 'jd':
                $id =14;
                $images ="jd.png";
                break;
            case 'bd':
                $id = 15;
                $images ="bd.png";
                break;
        }
        $sql = "select cate_icon,qr_code,opstate from ms_cate where id = {$id} and opstate = 1";
        $res = mysqli_query($db,$sql);
        $data = $res->fetch_array(MYSQLI_ASSOC);
        $qr_code = $data['qr_code'];
        $status = $data['opstate'];
        if(!$status){
              echo "<script > alert('该支付方式正在维护中，请更换支付方式重新支付');window.history.back();</script>";
        }
                    $orders_id =  $orders_id;
                    $orders_user = $_POST['name'];
                    $orders_money = $_POST['price'];
                    $client_ip = $_SERVER['REMOTE_ADDR'];
                    $create_time = date("Y-m-d H:i:s");
                    $sql = "insert into ms_company(orders_id,cate_id,orders_user,orders_money,client_ip,create_time) values('{$orders_id}','{$id}','{$orders_user}','{$orders_money}','{$client_ip}','{$create_time}')";
                    mysqli_query($db,$sql);
        ?>
        <div id="pawer">
            <div class="nav">
                <div class="nav-box">
                    <img src="../images/<?php echo $images; ?>" style="width:110%"/>
                </div>
            </div>
            <div class="set">
                <div class="set-box">
                    <div class="code-box">
                        <p class="code-txt">本次需充值<span class="code-txt1"><?php echo $_POST['price'];?></span>元</p>
                <div class="code">
                    <img src="http://image.8887060.com/<?php echo $qr_code;?>"/>
                </div>
                    <p class="code-ts">温馨提示：请务必按照以上提交金额进行支付，否则无法即时到账！</p>
                    </div>
                    <div class="det clearfix">
                        <p>交易单号<span><?php echo $orders_id;?></span></p>
                        <p>创建时间<span><?php echo $time;?></p>
                    </div>
                </div>
            </div>
            <div class="sub">
                <div class="sub-box" onclick="goback()"></div>
            </div>
            <div class="end">
                <div class="end-box">
                    <h2>扫码步骤</h2>
                    <p>1、<span>手动截屏二维码保存相册。</span></p>
                    <p>2、请在相应钱包中打开<span>“扫一扫”。</span></p>
                    <p>3、在扫一扫中点击右上角，选择<span>“从相册中选取二维码”</span>选取截屏的图片。</p>
                    <p>4、输入您欲充值的金额并进行转账。如充值未及时到账，请及时联系<span>【在线客服】。</span></p>
                </div>
            </div>
        </div>

    <?php
    }else{
        function sign_src($sign_fields, $map, $md5_key) {
            // 排序-字段顺序
            sort($sign_fields);
            $sign_src = "";
            foreach($sign_fields as $field) {
                $sign_src .= $field."=".$map[$field]."&";
            }
            $sign_src .= "KEY=".$md5_key;

            return $sign_src;
        }
        /**
         *
         * 计算md5签名
         *
         * 返回的签名数据位小写(给支付平台签名后的字母应为大写字母)
         * 在上传报文的时候需要注意将小写字母转为大写字母
         *
         **/
        function sign_mac($sign_fields, $map, $md5_key) {
            $sign_src = sign_src($sign_fields, $map, $md5_key);
            return md5($sign_src);
        }
        $sql = "select `merchant`,`key`,`url` from ms_cate where payname ='zspay' limit 1";
        $res = mysqli_query($db,$sql);
        $data = mysqli_fetch_array($res,MYSQLI_ASSOC);
        $merchantCode =$data['merchant']; //商户号
        $totalAmount = $_POST['price']*100;//充值金额
        $orderCreateTime = date("YmdHis");//订单创建时间
        $noticeUrl = $data['url']; //异步通知地址
        $merUrl = "http://www.8887060.com";
        $isSupportCredit = '1';
        $md5Key =$data['key']; //秘钥
        $outOrderId = $_POST['name'].'aazspayaa'.date("YmdHis"); //订单号
        $outUserId = "1";
        $url = "http://spayment.zsagepay.com/onlinebank/createOrder.do";
        $sign_fields = Array("merchantCode", "outOrderId", "totalAmount", "merchantOrderTime", "notifyUrl", "outUserId");
        $map = Array("merchantCode"=>$merchantCode, "outOrderId"=>$outOrderId, "totalAmount"=>$totalAmount, "merchantOrderTime"=>$orderCreateTime, "notifyUrl"=>$noticeUrl, "outUserId"=>$outUserId);
        $sign = sign_mac($sign_fields, $map, $md5Key);
        // 将小写字母转成大写字母
        $sign = strtoupper($sign);
        $random = rand(10000,99999);
        $orders_id =  $outOrderId;
        $orders_user = $_POST['name'];
        $orders_money = $_POST['price'];
        $ssl_sign = $sign;
        $client_ip = $_SERVER['REMOTE_ADDR'];
        $create_time = date("Y-m-d H:i:s");
         $sql = "insert into ms_online(orders_id,cate_id,orders_user,orders_money,ssl_sign,client_ip,create_time) values('{$orders_id}','48','{$orders_user}','{$orders_money}','{$ssl_sign}','{$client_ip}','{$create_time}')";
        mysqli_query($db,$sql);


        ?>
            <body onLoad="document.zlinepay.submit();">
        <form action="<?php echo ($url) ?>" name="zlinepay" method="post">
            <input type="hidden" class="form-control" name="merchantCode" value="<?php echo($merchantCode) ?>" />
            <input type="hidden" class="form-control"   name=outOrderId value="<?php echo ($outOrderId)?>" />
            <input type="hidden" class="form-control"   name="outUserId" value="1" />
            <input type="hidden" class="form-control"   name="totalAmount" value="<?php echo $totalAmount ?>" />
            <input type="hidden" class="form-control"   name="merUrl" value="<?php echo $merUrl ?>" />
            <input type="hidden" class="form-control"  name="notifyUrl" value="<?php echo $noticeUrl ?>">
            <input type="hidden" class="form-control"  name="merchantOrderTime" value="<?php echo ($orderCreateTime)?>" />
            <input type="hidden" class="form-control"  name="randomStr" value="<?php echo ($random)?>" />
            <input type="hidden" class="form-control"  name="sign" value="<?php echo($sign) ?>" />
        </form>
        <?php
    }

    ?>
    </body>
</html>
