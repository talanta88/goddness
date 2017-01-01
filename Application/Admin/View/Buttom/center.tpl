<extend name="Base/iframeBase" />
<block name="title">
    底部中間欄設置
    <a class="glyphicon glyphicon-plus btn btn-xs btn-danger pull-right tooltip_class" data-toggle="tooltip" data-placement="bottom" title="新增" href="__URL__/center_add"></a>
</block>
<block name="info">
    <table class="table table-bordered">
        <thead>
        <tr><th>序號</th><th>名稱</th><th>創建時間</th><th>更新時間</th><th>操作</th></tr>
        <thead>
        <tbody>
        <?php foreach($list as $v) : ?>
        <tr>
            <td>{$v.id}</td>
            <td>{$v.link_name}</td>
            <td>{$v.create_time}</td>
            <td>{$v.update_time}</td>
            <td>
                <a href="__URL__/center_add/id/{$v.id}" class="btn btn-success btn-xs" >編輯</a>
                &nbsp;&nbsp;<span class="btn btn-danger btn-xs del" val="{$v.id}">刪除</span>
            </td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <script type="text/javascript">
        $('.del').on('click',function() {
            var _this   = $(this);
            var id      = _this.attr('val');
            $.get("__URL__/center_del",{id:id},function(res) {
                $.dialog_show('e',res.i);
                if(res.t){
                    location.reload();
                }
            });
        });
    </script>
</block>