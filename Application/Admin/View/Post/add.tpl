<extend name="Base/iframeBase" />
<block name="title">
	新增顏色 <a href="__URL__/getList">【返回列表】</a>
</block>
<block name="info">
	<form class="form-horizontal" action="{:U('Post/add')}" method="post" name="permission">

        <div class="form-group">
            <label for="pname" class="col-md-2 control-label">選擇類型：</label>
            <div class="col-md-6">
                <select name="type" class="form-control">
                    <option value="">--請選擇--</option>
                    <?php foreach($type as $v) :?>
                        <option value="{$v.id}">{$v.name}</option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>

        <div class="form-group">
			<label for="name" class="col-md-2 control-label">郵寄方式：</label>
			<div class="col-md-6">
				<input type="text" name="name" placeholder="請輸入郵寄方式名稱" id="name" class="form-control"/>
			</div>
		</div>

        <div class="form-group">
            <label for="price" class="col-md-2 control-label">郵寄價格：</label>
            <div class="col-md-6">
                <input type="text" name="price" placeholder="請輸入郵寄價格" id="price" class="form-control"/>
            </div>
        </div>

		<div class="form-group">
			<div class="col-md-6 col-md-offset-2">
				<input type="submit" class="btn btn-primary btn-block" value="提交" />
			</div>
		</div>
	</form>
</block>