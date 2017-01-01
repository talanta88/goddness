<extend name="Base/iframeBase" />
<block name="title">
	新增優惠券 <a href="__URL__/getList">【返回列表】</a>
</block>
<block name="info">
	<form class="form-horizontal" action="{:U('Discountno/add')}" method="post" name="permission">

        <div class="form-group">
			<label for="name" class="col-md-2 control-label">優惠券號碼：</label>
			<div class="col-md-6">
				<input type="text" name="name" placeholder="請輸入優惠券號碼" id="name" class="form-control"/>
			</div>
		</div>
        <div class="form-group">
            <label for="name" class="col-md-2 control-label">優惠券類型：</label>
            <div class="col-md-6">
                <select class="form-control" name="type">
                    <option value="">--請選擇類型--</option>
                    <?php foreach($type as $key=>$val):?>
                    <option value="{$key}">{$val}</option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="form-group startTime">
            <label for="start" class="col-md-2 control-label">開始時間：</label>
            <div class="col-md-6">
                <input type="text" name="start" class="form-control" id="start" placeholder="請選擇開始時間" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="{$v.start}">
            </div>
        </div>

        <div class="form-group endTime">
            <label for="name" class="col-md-2 control-label">結束時間：</label>
            <div class="col-md-6">
                <input type="text" name="end" class="form-control" id="end" placeholder="請選擇結束時間" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="{$v.end}">
            </div>
        </div>

        <div class="form-group">
            <label for="price" class="col-md-2 control-label">折扣：</label>
            <div class="col-md-6">
                <input type="text" name="price" placeholder="請輸入折扣，如90/85" id="price" class="form-control"/>
            </div>
        </div>

		<div class="form-group">
			<div class="col-md-6 col-md-offset-2">
				<input type="submit" class="btn btn-primary btn-block" value="提交" />
			</div>
		</div>
	</form>
    <script type="application/javascript" src="__JS__/DatePicker/WdatePicker.js"></script>
</block>