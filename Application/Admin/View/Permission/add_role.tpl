<extend name="Base/iframeBase" />
<block name="title">
	<?php if(isset($mof_info)) :?>
		修改角色<a href="__URL__/getRoleList">【角色列表】</a>
	<?php else : ?>
		新增角色<a href="__URL__/getRoleList">【角色列表】</a>
	<?php endif; ?>
</block>
<block name="info">
	<?php if(!isset($mof_info)) :?>
		<form class="form-horizontal" action="{:U('Permission/addRole')}" method="post" name="role">
	<?php else : ?>
		<form class="form-horizontal" action="{:U('Permission/mofRoleInfo')}" method="post" name="role">
		<input type="hidden" name="id" value="<?php echo $mof_info['id'] ?>" />
	<?php endif; ?>
		<div class="form-group">
			<label for="name" class="col-md-2 control-label">角色名称：</label>
			<div class="col-md-8">
				<input type="text" name="name" placeholder="请输入角色名称，30个字以内" id="name" class="form-control" value="<?php echo $mof_info['name'] ?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="permission" class="col-md-2 control-label">角色权限：</label>
			<div class="col-md-8">
				<table class="table table-striped table-bordered">
					<?php foreach($per_info as $key => $value) :?>
						<tr>
							<td width="20%">
								<input class="per_checkbox" parent_id="<?php echo $value['parent_id'] ?>" name="per[]" type="checkbox" value="<?php echo $value['id'] ?>" <?php if(in_array($value['id'],explode(',', $mof_info['permission_ids']))) echo 'checked';?>/> <?php echo $value['name'];?>
							</td>
							<?php if(isset($value['son'])) :?>
								<td><dl>
									<?php foreach($value['son'] as $k => $v) :?>
										<dd><input class="per_checkbox" parent_id="<?php echo $v['parent_id'] ?>" name="per[]" type="checkbox" value="<?php echo $v['id'] ?>" <?php if(in_array($v['id'],explode(',', $mof_info['permission_ids']))) echo 'checked';?> /> <?php echo $v['name'] ?> </dd>
									<?php endforeach; ?></dl>
								</td>
							<?php endif; ?>
						</tr>
					<?php endforeach;?>
				</table>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-8 col-md-offset-2">
				<input type="submit" class="btn btn-primary btn-block" value="提交" />
			</div>
		</div>
	</form>
</block>