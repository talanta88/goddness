<extend name="Base/iframeBase"/>
<block name="title">
    條幅管理
    <!--引入文本编辑器-->
    <link href="__PUBLIC__/UEditor/themes/default/default.css" rel="stylesheet"/>
    <script src="__PUBLIC__/UEditor/kindeditor-min.js"/></script>
    <script src = "__PUBLIC__/UEditor/plugins/code/prettify.js" ></script>
    <script src="__PUBLIC__/UEditor/lang/zh_TW.js"></script>
    <script type="text/javascript">
        KindEditor.ready(function (K) {
            var editor1 = K.create('textarea[name="title"]', {
                allowFileManager: true,
                afterBlur: function () {
                    this.sync();
                },
            });
            prettyPrint();
        });
    </script>
    <!--引入文本编辑器-->
</block>
<block name="info">
    <form class="form-horizontal" action="{:U('Salespromotion/update')}" method="post" name="permission">
        <div class="form-group">
            <input type="hidden" name="id" value="{$info.id}"/>
            <label for="name" class="col-md-2 control-label">折扣条幅：</label>
            <div class="col-md-6">
                <textarea class="form-control" name="title" rows="10"
                          placeholder="折扣条幅">{$info.title}</textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6 col-md-offset-2">
                <input type="submit" class="btn btn-primary btn-block" value="提交"/>
            </div>
        </div>
    </form>
</block>