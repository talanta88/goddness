<extend name="Base/iframeBase" />
<block name="title">
	優惠券列表
	<a class="glyphicon glyphicon-plus btn btn-xs btn-danger pull-right tooltip_class" data-toggle="tooltip" data-placement="bottom" title="新增" href="__URL__/add"></a>
</block>
<block name="info">
	<?php if(empty($list)) :?>
		<span>沒有任何信息</span>
	<?php else : ?>
		<table class="table table-bordered">
		<thead>
			<tr><th>優惠券碼</th><th>折扣</th><th>開始時間</th><th>結束時間</th><th>是否已用</th><th>操作</th></tr>
		<thead>
		<tbody>
            <?php foreach($list as $v) : ?>
                <tr>
                    <td>{$v.no}</td>
                    <td>{$v.price}</td>
                    <td><?php if($v['start']){echo date('Y-m-d H:i:s',$v['start']);}else{ echo '無';} ?></td>
                    <td><?php if($v['end']){echo date('Y-m-d H:i:s',$v['end']);}else{ echo '無';}?></td>
                    <td><?php if($v['is_used']) echo '已經使用';else echo '沒有'; ?></td>
                    <td><span class="btn btn-danger btn-xs del" val="{$v.id}">刪除</span></td>
                </tr>
            <?php endforeach;?>
            <tr>
                <td colspan="6"><?php echo $page; ?></td>
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