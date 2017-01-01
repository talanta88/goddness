<extend name="Base/iframeBase" />
<block name="title">
	新增郵費優惠 <a href="__URL__/getList">【返回列表】</a>
</block>
<block name="info">
	<form class="form-horizontal">
        <div class="form-group">
			<label for="name" class="col-md-2 control-label">優惠金額條件：</label>
			<div class="col-md-6">
				<input type="text" name="rule_money" placeholder="請輸入優惠金額條件" id="name" class="form-control"/>
			</div>
		</div>
        <div class="form-group">
            <label for="price" class="col-md-2 control-label">優惠金額：</label>
            <div class="col-md-6">
                <input type="text" name="discount_money" placeholder="請輸入優惠金額" id="price" class="form-control"/>
            </div>
        </div>
		<div class="form-group">
			<div class="col-md-6 col-md-offset-2">
				<button type="button" class="btn btn-primary btn-block" id="save">提交</button>
			</div>
		</div>
	</form>
    <script>
        $('#save').click(function() {
            var _this   = $(this);
            var discount_money = $('input[name=discount_money]').val();
            var rule_money = $('input[name=rule_money]').val();
            $.get("__URL__/addPost",{discount_money:discount_money,rule_money:rule_money},function(res) {
                    $.dialog_show('e',res.i);
            });
        });
    </script>
</block>
