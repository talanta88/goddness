<extend name="Base/iframeBase" />
<block name="title">
    注意事项列表
    <a class="glyphicon glyphicon-plus btn btn-xs btn-danger pull-right tooltip_class" data-toggle="tooltip" data-placement="bottom" title="新增" href="__URL__/add"></a>
</block>
<block name="info">
    <table class="table table-bordered">
        <thead>
        <tr><th>序號</th><th>事項名稱</th><th>創建時間</th><th>修改時間</th><th>操作</th></tr>
        <thead>
        <tbody>
        <?php foreach($list as $v) : ?>
        <tr>
            <td>{$v.id}</td>
            <td>{$v.name}</td>
            <td>{$v.create_time}</td>
            <td>{$v.modify_time}</td>
            <td>
                <a href="__URL__/addAttention/id/{$v.id}" class="btn btn-primary btn-xs">編輯/詳情</a>
                <span class="btn btn-danger btn-xs del" val="{$v.id}" >刪除</span>
            </td>
        </tr>
        <?php endforeach;?>
        <!--<tr>
            <td colspan="4"><?php echo $page; ?></td>
        </tr>-->
        </tbody>
    </table>
    <script type="text/javascript">
        $('.del').on('click',function() {
            var _this   = $(this);
            var id      = _this.attr('val');
            $.get("__URL__/removeAttention",{id:id},function(res) {
                if(res.status == 0){
                    $.dialog_show('e',res.data);
                    return false;
                }else {
                    _this.parent().parent().remove();
                }
            });
        });
    </script>
</block>