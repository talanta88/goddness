<extend name="Base/iframeBase" />
<block name="title">
	新增权限 <a href="__URL__/getPermissionList">【返回列表】</a>
</block>
<block name="info">
	<form class="form-horizontal" action="{:U('Permission/addPermission')}" method="post" name="permission">
		<div class="form-group">
			<label for="name" class="col-md-2 control-label">权限名称：</label>
			<div class="col-md-6">
				<input type="text" name="name" placeholder="请输入权限名称，30个字以内" id="name" class="form-control"/>
			</div>
			
		</div>
		<div class="form-group">
			<label for="contro_name" class="col-md-2 control-label">控制器名称：</label>
			<div class="col-md-6">
				<input type="text" name="contro_name" placeholder="控制器名称不能为空" id="contro_name" class="form-control"/>
			</div>
		</div>
		<div class="form-group">
			<label for="action_name" class="col-md-2 control-label">方法名称：</label>
			<div class="col-md-6">
				<input type="text" name="action_name" placeholder="类别为子类时，方法名称不能为空" id="action_name" class="form-control"/>
			</div>
		</div>
		<div class="form-group">
			<label for="parent_id" class="col-md-2 control-label">所属类别：</label>
			<div class="col-md-6">
				<select class="form-control" id="parent_id" name="parent_id">
					<option value="0">--主类别--</option>
					<?php 
						if(!empty($info)) {
							foreach ($info as $key => $value) {
								echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
							}
						}
					?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="show" class="col-md-2 control-label">是否展示：</label>
			<div class="col-md-6">
				<input type="radio" name="is_show" id="show" value="1"/> 是
				<input type="radio" name="is_show" id="show" value="0" checked/> 否
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6 col-md-offset-2">
				<input type="submit" class="btn btn-primary btn-block" value="提交" />
			</div>
		</div>
	</form>
</block>