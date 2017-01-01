<extend name="Base/iframeBase" />
<block name="title">
    倒計時管理 <stront style="color:red;font-size:22px;">{$str.err_str}</stront>
</block>
<block name="info">
    <form class="form-inline" method="post" action="{:U('Time/add')}">
        <div class="form-group">
            <label for="start">開始時間：</label>
            <input type="text" name="start" class="form-control" id="start" placeholder="請選擇開始時間" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="{$info.start}">
        </div>
        <div class="form-group">
            <label for="start">結束時間：</label>
            <input type="text" name="end" class="form-control" id="end" placeholder="請選擇結束時間" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="{$info.end}">
        </div>
        <button type="submit" class="btn btn-primary submit">提交</button>
    </form>
    <script type="application/javascript" src="__JS__/DatePicker/WdatePicker.js"></script>
    <script type="application/javascript">
        $('.submit').on('click',function() {
            var start           = $('#start').val(),
                end             = $('#end').val();

            if(start.length <= 0) {
                $.dialog_show('e', '請選擇開始時間');
                $('#start').focus();
                return false;
            }

            if(end.length <= 0){
                $.dialog_show('e','請選擇結束時間');
                $('#end').focus();
                return false;
            }
        });
    </script>
</block>
