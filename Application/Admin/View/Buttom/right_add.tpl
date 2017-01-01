<extend name="Base/iframeBase" />
<block name="title">
    底部右欄新增
    <!--引入文本编辑器-->
    <link href="__PUBLIC__/UEditor/themes/default/default.css" rel="stylesheet"/>
    <script src="__PUBLIC__/UEditor/kindeditor-min.js" /></script>
    <script src="__PUBLIC__/UEditor/plugins/code/prettify.js" ></script>
    <script src="__PUBLIC__/UEditor/lang/zh_TW.js" ></script>
    <script type="text/javascript">
        KindEditor.ready(function(K) {
            var editor1 = K.create('textarea[name="content"]', {
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
    <form class="form-horizontal" action="{:U('Buttom/right_add_action')}" method="post" name="permission">
        <input type="hidden" name="id" value="{$link.id}" />
        <div class="form-group">
            <label for="name" class="col-md-2 control-label">鏈接名稱：</label>
            <div class="col-md-6">
                <input type="text" name="link_name" placeholder="鏈接名稱" id="link_name" class="form-control" value="{$link.link_name}"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-2 control-label">頁面內容：</label>
            <div class="col-md-8">
                <textarea class="form-control" name="content" rows="10" placeholder="頁面內容">{$link.content}</textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-8 col-md-offset-2">
                <input type="submit" class="btn btn-primary btn-block" value="提交" />
            </div>
        </div>
    </form>
    <script type="text/javascript">
        $('.upimg_multi').find('input[type="file"]').on('change',function() {
            var _this  			= $(this);
            var img             = this.files[0];
            var _type            = img.type;
            if(_type.substr(0,5) != 'image'){
                $.dialog_show('e','非图片类型，无法上传！');
                return false;
            }
            var fd              = new FormData();
            fd.append('param',img);
            $.ajax({
                type        : 'post',
                url         : Think.MODULE + '/File/upload',
                data        : fd,
                processData : false,
                contentType : false,
                dataType    : 'json',
                beforeSend 	: function() {
                    $('input[type="submit"]').attr({'disabled' : 'disabled'}).val('等待图片上传完毕...');
                    _this.prev().html('...');
                },
                success     : function(res) {
                    if(typeof res.file_id != undefined) {
                        _this.prev().html('+');
                        _this.parent().find('.img-thumbnail').attr({'src' : res.url}).fadeIn();
//                        _this.parent().next().show();
                        _this.parent().find('.color_dir').val(res.img_dir);
                    }else {
                        $.dialog_show('e',res);
                    }
                    $('input[type="submit"]').removeAttr('disabled').val('提交');
                }
            });
        });
    </script>
</block>