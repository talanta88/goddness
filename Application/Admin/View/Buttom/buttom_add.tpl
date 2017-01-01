<extend name="Base/iframeBase" />
<block name="title">
    底部標題新增
</block>
<block name="info">
    <form class="form-horizontal" action="{:U('Buttom/buttom_title_add_action')}" method="post" name="permission">
        <input type="hidden" name="id" value="{$title.id}" />
        <div class="form-group">
            <label for="name" class="col-md-2 control-label">標題位置：</label>
            <div class="col-md-6">
                <select name="title_type">
                    <option value="">--請選擇--</option>
                    <?php foreach($titleType as $k=>$v):?>
                    <option value="{$k}" <?php if($k==$title['type']):?>selected="selected"<?php endif;?>>{$v}</option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-md-2 control-label">標題名稱：</label>
            <div class="col-md-6">
                <input type="text" name="title" placeholder="標題名稱" id="title" class="form-control" value="{$title.title}"/>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-8 col-md-offset-2">
                <input type="submit" class="btn btn-primary btn-block" value="提交" />
            </div>
        </div>
    </form>
</block>