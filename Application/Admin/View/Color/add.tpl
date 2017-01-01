<extend name="Base/iframeBase" />
<block name="title">
	新增顏色 <a href="__URL__/getList">【返回列表】</a>
</block>
<block name="info">
	<form class="form-horizontal" action="{:U('Color/add')}" method="post" name="permission">

        <div class="form-group">
			<label for="name" class="col-md-2 control-label">顏色名稱：</label>
			<div class="col-md-6">
				<input type="text" name="name" placeholder="請輸入顏色名稱" id="name" class="form-control"/>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6 col-md-offset-2">
				<input type="submit" class="btn btn-primary btn-block" value="提交" />
			</div>
		</div>
	</form>
</block>