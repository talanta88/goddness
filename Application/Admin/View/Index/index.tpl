<extend name="Base/iframeBase" />
<block name="title">
	首页
</block>
<block name="info">
	<table class="table table-bordered">
        <tr ><th colspan="2" class="text-center">服务器信息</th></tr>
        <?php foreach($info as $key => $value):?>
            <tr><td><?php echo $key;?></td><td><?php echo $value;?></td></tr>
        <?php endforeach;?>
    </table>
</block>