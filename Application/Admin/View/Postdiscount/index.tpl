<extend name="Base/iframeBase" />
<block name="title">
	郵費優惠列表
	<a class="glyphicon glyphicon-plus btn btn-xs btn-danger pull-right tooltip_class" data-toggle="tooltip" data-placement="bottom" title="新增" href="__URL__/add"></a>
</block>
<block name="info">
		<table class="table table-bordered">
		<thead>
			<tr><th>序號</th><th>優惠金額條件</th><th>優惠金額</th><th>狀態</th><th>操作</th></tr>
		<thead>
		<tbody>
            <?php foreach($list as $v):?>
                <tr>
                    <td>{$v.id}</td>
                    <td>{$v.rule_money}</td>
                    <td>{$v.discount_money}</td>
                    <td><?php if($v['status']==1):?>已啟用<?php else:?>未啟用<?php endif;?></td>
                    <td><span class="btn btn-danger btn-xs del" val="{$v.id}">删除</span></td>
                </tr>
            <?php endforeach;?>
		</tbody>
		</table>
    <script type="text/javascript">
        $('.del').on('click',function() {
            var _this   = $(this);
            var id      = _this.attr('val');
            $.get("__URL__/delPost",{id:id},function(res) {
                if(res.status == 0){
                    $.dialog_show('e',res.i);
                    return false;
                }else {
                    _this.parent().parent().remove();
                }
            });
        });
    </script>
</block>