<extend name="Base/iframeBase" />
<block name="title">
	角色列表
	<a class="glyphicon glyphicon-plus btn btn-xs btn-danger pull-right tooltip_class" data-toggle="tooltip" data-placement="bottom" title="新增" href="__URL__/addRole"></a>
</block>
<block name="info">
	<?php if(empty($info)) :?>
		<span>没有任何信息</span>
	<?php else : ?>
		<table class="table table-bordered">
		<thead>
			<tr><th>角色名称</th><th>操作</th></tr>
		<thead>
		<tbody>
			<?php foreach($info as $key => $value) :?>
				<tr value="<?php echo $value['id']; ?>">
					<td><?php echo $value['name']; ?></td>
					<td><a href="{:U('Permission/mofRoleInfo',array('id'=>$value['id']))}" class="btn btn-xs btn-primary">修改</a> | <a class="role_del btn btn-xs btn-primary">删除</a></td>
				</tr>
			<?php endforeach?>
		</tbody>
		</table>
	<?php endif; ?>
</block>