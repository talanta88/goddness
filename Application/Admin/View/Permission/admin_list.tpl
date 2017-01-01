<extend name="Base/iframeBase" />
<block name="title">
	管理员列表
	<a class="glyphicon glyphicon-plus btn btn-xs btn-danger pull-right tooltip_class" data-toggle="tooltip" data-placement="bottom" title="新增" href="__URL__/adminAdd"></a>
</block>
<block name="info">
	<?php if(empty($info)) :?>
		<span>没有任何信息</span>
	<?php else : ?>
		<table class="table table-hover table-condensed">
		<thead>
			<tr><th>登录账号</th><th>最后登录时间</th><th>最后登录ip</th><th>超级管理员</th><th>操作</th></tr>
		<thead>
		<tbody>
			<?php foreach($info as $key=>$value) :?>
				<tr>
					<td><?php echo $value['name']?></td>
					<td><?php echo $value['last_time'] ? date('Y-m-d',$value['last_time']) : '无'?></td>
					<td><?php echo $value['last_ip'] ? long2ip($value['last_ip']) : '无'?></td>
					<td><?php echo $value['is_super_str']?></td>
					<td><a href="{:U('Permission/adminMof',array('id' => $value['id']))}" class="btn btn-xs btn-primary">修改</a> | <a class="admin_del btn btn-xs btn-primary" value="<?php echo $value['id'] ?>">删除</a></td><tr>
			<?php endforeach; ?>
		</tbody>
		</table>
	<?php endif; ?>
</block>