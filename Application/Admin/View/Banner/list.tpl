<extend name="Base/iframeBase" />
<block name="title">
	一級品項列表
	<a class="glyphicon glyphicon-plus btn btn-xs btn-danger pull-right tooltip_class" data-toggle="tooltip" data-placement="bottom" title="新增" href="__URL__/add"></a>
</block>
<block name="info">
	<?php if(empty($list)) :?>
		<span>沒有任何信息</span>
	<?php else : ?>
		<table class="table table-bordered">
		<thead>
			<tr><th>一級品項名稱</th><th>添加時間</th><th>操作</th></tr>
		<thead>
		<tbody>
            <?php foreach($list as $v) : ?>
                <tr>
                    <td>{$v.name}</td>
                    <td><?php echo date('Y-m-d H:i',$v['add_time']); ?></td>
                    <td>
                        <a href="__URL__/mof/id/{$v.id}" class="btn btn-success btn-xs" val="{$v.id}">編輯/詳情</a>
                        <span class="btn btn-danger btn-xs del" val="{$v.id}">刪除</span>
                    </td>
                </tr>
            <?php endforeach;?>
            <tr>
                <td colspan="3"><?php echo $page; ?></td>
            </tr>
		</tbody>
		</table>
	<?php endif; ?>
    <script type="text/javascript">
        $('.del').on('click',function() {
            var _this   = $(this);
            var id      = _this.attr('val');
            $.get("__URL__/del",{id:id},function(res) {
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