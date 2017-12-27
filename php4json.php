<?php header("Content-type: text/html; charset=utf-8"); ?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
    <meta http-equiv="refresh" content="5">
</head>
<body>
<?php
$now = date('Y-m-d H:i:s');
echo '<br/>演示内容：采用PHP进行JSON格式采集并分析数据。';
echo '<br/>默认演示为《开彩网》重庆时时彩免费开奖接口';
echo '<br/>付费接口的采集格式与免费接口完全一致';
echo '<br/>如需使用付费接口，请修改采集为对应地址';
echo '<br/>如有其它疑问请访问<a href="http://www.opencai.net/"><b style="color:#f00">www.opencai.net</b></a>';
echo '<br/>';

$src = 'http://f.apiplus.cn/newly.do?code=cqssc&format=json';
echo "<br/>采集地址：{$src}<br/><br/>";
//防止GET本地缓存，增加随机数
$src .= (strpos($src,'?')>0 ? '&':'?').'_='.time();
$html = file_get_contents($src);
$json = json_decode($html,true);

if (isset($json['rows'])){
	echo "{$now}共采集到{$json['rows']}行开奖数据：<br/>";
	foreach($json['data'] as $r){
		$expect = preg_replace("/^(\d{8})(\d{3})$/","\\1-\\2",$r['expect']);
		$opencode = $r['opencode'];
		$opentime = $r['opentime']
		echo "开奖期号：{$expect}<br/>";
		echo "开奖号码：{$opencode}<br/>";
		echo "开奖时间：{$opentime}<br/>";
		echo '<br/>';
		//TODO: 分析数据、对比数据，并写入数据库
	}
}else{
	echo "采集失败，返回提示：<br/>";
	echo $html;
}
?>
</body>
</html>