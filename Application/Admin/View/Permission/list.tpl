<extend name="Base/iframeBase" />
<block name="title">
	权限列表
	<a class="glyphicon glyphicon-plus btn btn-xs btn-danger pull-right tooltip_class" data-toggle="tooltip" data-placement="bottom" title="新增" href="__URL__/addPermission"></a>
</block>
<block name="info">
	<?php if(empty($info)) :?>
		<span>没有任何信息</span>
	<?php else : ?>
		<table class="table table-bordered">
		<thead>
			<tr><th>权限名称</th><th>控制器名称</th><th>方法名称</th><th>父类</th><th>是否展示</th><th>操作</th></tr>
		<thead>
		<tbody>
			<?php foreach($info as $key=>$value) :?>
				<tr sonrole="<?php echo $value['id'] ?>">
					<td><?php if(isset($value['son'])): ?><span class="glyphicon glyphicon-zoom-in cursor btn_color_share get_son"></span><?php endif;?> <?php echo $value['name']?></td>
					<td><?php echo $value['contro_name']?></td>
					<td><?php echo $value['action_name']?></td>
					<td></td>
					<td><a class="show" id="<?php echo $value['id'] ?>"><?php echo $value['is_show']?></a></td>
					<td><a parent_id="<?php echo $v['parent_id'] ?>" id="<?php echo $value['id'] ?>" class="del btn btn-xs btn-primary">删除</a></td>
				</tr>
				<?php if(isset($value['son'])): ?>
					<?php foreach($value['son'] as $k => $v) : ?>
						<tr role="<?php echo $v['parent_id'] ?>" style="display: none;"><td>　　<?php echo $v['name']?></td><td><?php echo $v['contro_name']?></td><td><?php echo $v['action_name']?></td><td><?php echo $value['name']?></td><td><a class="show" id="<?php echo $v['id'] ?>"><?php echo $v['is_show']?></a></td><td><a parent_id="<?php echo $v['parent_id'] ?>" id="<?php echo $v['id'] ?>" class="del btn btn-xs btn-primary">删除</a></td></tr>
					<?php endforeach;?>
				<?php endif;?>
			<?php endforeach; ?>
		</tbody>
		</table>
	<?php endif; ?>
</block>