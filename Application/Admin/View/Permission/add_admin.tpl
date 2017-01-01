<extend name="Base/iframeBase" />
<block name="title">
	<?php if(!isset($mof_info)) :?>
		新增管理员<a href="{:U('Permission/adminList')}">【管理员列表】</a>
	<?php else : ?>
		修改管理员<a href="{:U('Permission/adminList')}">【管理员列表】</a>
	<?php endif; ?>
</block>
<block name="info">
	<?php if(!isset($mof_info)) :?>
		<form class="form-horizontal" action="{:U('Permission/adminAdd')}" method="post" name="admin">
	<?php else : ?>
		<form class="form-horizontal" action="{:U('Permission/adminMof')}" method="post" name="admin">
		<input type="hidden" name="id" value="<?php echo $mof_info['id'] ?>" />
	<?php endif; ?>

		<div class="form-group">
			<label for="name" class="col-md-2 control-label">账号：</label>
			<div class="col-md-8">
				<input type="text" name="name" placeholder="管理员登录账号，30字内" id="name" class="form-control" value="<?php echo $mof_info['name'] ?>"/>
			</div>
		</div>
		
		<div class="form-group">
			<label for="pass" class="col-md-2 control-label">密码：</label>
			<div class="col-md-8">
				<input type="text" name="pass" placeholder="管理员密码，建议使用随机密码，至少6位" id="pass" class="form-control"/>
			</div>
		</div>

		<div class="form-group">
			<label  class="col-md-2 control-label">超级管理员：</label>
			<div class="col-md-8">
				<?php if(!isset($mof_info)) :?>
					<input type="radio" name="is_super" checked value="0" disabled/> 否
				<?php else : ?>
					<input type="radio" name="is_super" value="0"  <?php if($mof_info['is_super'] == 0) echo 'checked'; else echo 'disabled'?>/> 否
					<input type="radio" name="is_super" value="1"  <?php if($mof_info['is_super'] == 1) echo 'checked'; else echo 'disabled'?>/> 是
				<?php endif;?>
			</div>
		</div>

		<?php if($mof_info['is_super'] != 1) :?>
			<div class="form-group">
				<label class="col-md-2 control-label">管理员角色：</label>
				<div class="col-md-8">
					<?php if(empty($role_info)) :?>
						暂无任何角色
					<?php else : ?>
						<?php foreach($role_info as $key => $value) :?>
							<input type="checkbox" name="rol[]" value="<?php echo $value['id']; ?>" <?php if(in_array($value['id'], explode(',', $mof_info['role_ids']))) echo 'checked'?>/> <?php echo $value['name'];?>
						<?php endforeach; ?>
					<?php endif;?>
				</div>
			</div>
		<?php endif;?>

		<div class="form-group">
			<div class="col-md-8 col-md-offset-2">
				<input type="submit" class="btn btn-primary btn-block" value="提交" />
			</div>
		</div>
	</form>
</block>