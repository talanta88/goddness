<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ 
	background: #fff; 
	font-family: "Arial","Microsoft YaHei","黑体","宋体",sans-serif; 
	color: #333; 
	font-size: 16px; 
}
.system-info {
	width:650px;
	height:200px;
	background:#ECEAEA;
	border:1px solid #ECEAEA;
	border-radius: 4px;
	margin:100px auto;
	position: relative;
}
.system-info .success,.system-info .error {
	font-size: 22px;
	margin:50px 0 0 0;
	text-align: center;
	padding:0 0 5px 0;
	border-bottom:1px solid #356EBB;
}
.info {
	color:orange;
}
.jump {
	text-align: right;
	margin-bottom: 5px;
	position: absolute;
	top:170px;
	right:20px;
}
.detail {
	font-size: 14px;
}
</style>
</head>
<body>

<div class="system-info">
	<?php if(isset($message)) {?>
		<p class="success">操作成功：<span class="info"><?php echo($message); ?></span></p>
	<?php }else{?>
		<p class="error">操作失败：<span class="info"><?php echo($error); ?></span></p>
	<?php }?>
	<p class="detail"></p>
	<p class="jump">页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b></p>
</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
<?php if(isset($message)) die();?>
</body>
</html>