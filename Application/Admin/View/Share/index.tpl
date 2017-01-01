<extend name="Base/iframeBase" />
<block name="title">
    頂部分享代碼列表
    <a class="glyphicon glyphicon-plus btn btn-xs btn-danger pull-right tooltip_class" data-toggle="tooltip" data-placement="bottom" title="新增" href="__URL__/add"></a>
</block>
<block name="info">
    <table class="table table-bordered">
        <thead>
        <tr><th>序號</th><th>分享样式</th><th>創建時間</th><th>操作</th></tr>
        <thead>
        <tbody>
        <?php foreach($list as $v) : ?>
        <tr>
            <td>{$v.id}</td>
            <td>{$v.content|html_entity_decode}</td>
            <td><?php echo date('Y-m-d H:i:s',$v['add_time']); ?></td>
            <td>
                <a href="__URL__/addShare/id/{$v.id}" class="btn btn-primary btn-xs">編輯/詳情</a>
                <span class="btn btn-danger btn-xs del" val="{$v.id}" bid="{$v.bid}">关闭</span>
                <span class="btn btn-danger btn-xs open" val="{$v.id}" bid="{$v.bid}">启用</span>
            </td>
        </tr>
        <?php endforeach;?>
        <tr>
            <td colspan="4"><?php echo $page; ?></td>
        </tr>
        </tbody>
    </table>
    <script type="text/javascript">
        $('.del').on('click',function() {
            var _this   = $(this);
            var id      = _this.attr('val');
            $.get("__URL__/closeShare",{id:id},function(res) {
                if(res.status == 0){
                    $.dialog_show('e',res.data);
                    return false;
                }else {
                    _this.parent().parent().remove();
                }
            });
        });
        $('.open').on('click',function() {
            var _this   = $(this);
            var id      = _this.attr('val');
            $.get("__URL__/openShare",{id:id},function(res) {
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