<extend name="Base/iframeBase" />
<block name="title">
    <?php if(isset($info)) : ?>
    修改
	<?php else :?>
    新增
    <?php endif;?>
    一級品項
    <a href="__URL__/getList">【返回列表】</a>

    <!--引入文本编辑器-->
    <link href="__PUBLIC__/UEditor/themes/default/default.css" rel="stylesheet"/>
    <script src="__PUBLIC__/UEditor/kindeditor-min.js" /></script>
    <script src="__PUBLIC__/UEditor/plugins/code/prettify.js" ></script>
    <script src="__PUBLIC__/UEditor/lang/zh_TW.js" ></script>
    <script type="text/javascript">
        KindEditor.ready(function(K) {
            var editor1 = K.create('textarea[name="description"]', {
                allowFileManager : true,
                afterBlur:function(){
                    this.sync();
                },
            });
            prettyPrint();
        });
    </script>
    <!--引入文本编辑器-->
</block>
<block name="info">
    <?php if(isset($info)) : ?>
        <form class="form-horizontal" action="{:U('Banner/mof')}" method="post" name="permission">
            <input type="hidden" name="id" value="{$info.id}" />
    <?php else :?>
        <form class="form-horizontal" action="{:U('Banner/add')}" method="post" name="permission">
    <?php endif;?>

        <div class="form-group">
			<label for="name" class="col-md-2 control-label">品項名稱：</label>
			<div class="col-md-6">
				<input type="text" name="name" placeholder="請輸入品項名稱" id="name" class="form-control" value="{$info.name}"/>
			</div>
		</div>

        <!--<div class="form-group">
            <label for="missmoney" class="col-md-2 control-label">免運金額(0表示不免)：</label>
            <div class="col-md-6">
                <input type="text" name="missmoney" placeholder="請輸入免運金額" id="missmoney" class="form-control" value="{$info.missmoney}"/>
            </div>
        </div>-->
       <!--<div class="form-group">
            <label for="name" class="col-md-2 control-label">折扣描述：</label>
            <div class="col-md-6">
                <textarea class="form-control" name="description" rows="10" placeholder="折扣描述">{$info.description}</textarea>
            </div>
        </div>-->

        <div class="dis" style="display:none;">
            <div class="form-group">
                <label for="name" class="col-md-2 control-label">折扣 <span class="btn btn-xs btn-danger tooltip_class deldis" data-toggle="tooltip" data-placement="top" title="" data-original-title="刪除折扣">﹣</span>：</label>
                <div class="col-md-3">
                    <input type="text" name="dis[count][]" placeholder="請輸入件數" id="name" class="form-control"/>
                </div>
                <div class="col-md-3">
                    <input type="text" name="dis[dis][]" placeholder="請輸入折扣率(95/85/...)" id="name" class="form-control"/>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="name" class="col-md-2 control-label">折扣 <span class="btn btn-xs btn-success tooltip_class adddis" data-toggle="tooltip" data-placement="top" title="" data-original-title="添加新折扣">＋</span>
                ：</label>
            <div class="col-md-3">
                <input type="text" name="dis[count][]" placeholder="請輸入件數" id="name" class="form-control"/>
            </div>
            <div class="col-md-3">
                <input type="text" name="dis[dis][]" placeholder="請輸入折扣率(95/85/...)" id="name" class="form-control"/>
            </div>
        </div>

        <?php if(isset($info) && count($info['dis']) > 0) : ?>
            <?php foreach($info['dis'] as $k => $v) : ?>
                <div class="form-group">
                    <label for="name" class="col-md-2 control-label">折扣 <span class="btn btn-xs btn-danger tooltip_class deldis" data-toggle="tooltip" data-placement="top" title="" data-original-title="刪除折扣">﹣</span>：</label>
                    <div class="col-md-3">
                        <input type="text" name="dis[count][]" placeholder="請輸入件數" id="name" class="form-control" value="{$v.count}"/>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="dis[dis][]" placeholder="請輸入折扣率(95/85/...)" id="name" class="form-control" value="{$v.discount}"/>
                    </div>
                </div>
            <?php endforeach;?>
        <?php endif;?>

		<div class="form-group">
			<div class="col-md-6 col-md-offset-2">
				<input type="submit" class="btn btn-primary btn-block" value="提交" />
			</div>
		</div>
	</form>
    <script type="text/javascript">
        $('.adddis').on('click',function() {
            var html        = $('.dis').html();
            var _this       = $(this);

            _this.parent().parent().after(html);
        });

        $(document).on('click','.deldis',function() {
            $(this).parent().parent().remove();
        });
    </script>
</block>