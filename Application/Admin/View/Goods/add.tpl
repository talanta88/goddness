<extend name="Base/iframeBase" />
<block name="title">
    <?php if(isset($info)) : ?>
    更新產品 <a href="__URL__/getList">【返回列表】</a>
    <?php else : ?>
    新增產品 <a href="__URL__/getList">【返回列表】</a>
    <?php endif;?>
    <!--引入文本编辑器-->
    <link href="__PUBLIC__/UEditor/themes/default/default.css" rel="stylesheet"/>
    <script src="__PUBLIC__/UEditor/kindeditor-min.js" /></script>
    <script src="__PUBLIC__/UEditor/plugins/code/prettify.js" ></script>
    <script src="__PUBLIC__/UEditor/lang/zh_TW.js" ></script>
    <script type="text/javascript">
        KindEditor.ready(function(K) {
            var editor1 = K.create('textarea[name="info"]', {
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
        <form class="form-horizontal" action="{:U('Goods/mof')}" method="post" name="permission">
            <input type="hidden" name="id" value="{$info.id}" />
            <input type="hidden" name="bid" value="{$info.bid}" />
            <input type="hidden" name="add_time" value="{$info.add_time}" />
    <?php else : ?>
        <form class="form-horizontal" action="{:U('Goods/add')}" method="post" name="permission">
    <?php endif;?>

        <div class="form-group">
            <label for="parent_id" class="col-md-2 control-label">第一層品項：</label>
            <div class="col-md-8">
                <select class="form-control" id="parent_id" name="parent_id">
                    <option value="0">--請選擇--</option>
                    <?php foreach($need['b'] as $v) :?>
                    <option value="{$v.id}" <?php if($info['pid'] == $v['id']) echo 'selected="selected"';?>>{$v.name}</option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="post_type" class="col-md-2 control-label">運送分類：</label>
            <div class="col-md-8">
                <select class="form-control" id="post_type" name="post_type">
                    <option value="0">--請選擇--</option>
                    <?php foreach($need['p'] as $v) :?>
                    <option value="{$v.id}" <?php if($info['post_type'] == $v['id']) echo 'selected="selected"';?>>{$v.name}</option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-md-2 control-label">產品編號：</label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="self_product_id"  placeholder="產品編號" value="<?php if(isset($info)) echo $info['self_product_id'];?>"/>
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-md-2 control-label">產品名稱：</label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="name" id="name" placeholder="產品名稱" value="<?php if(isset($info)) echo $info['name'];?>"/>
            </div>
        </div>

        <div class="form-group">
            <label for="price" class="col-md-2 control-label">產品价格：</label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="price" id="price" placeholder="產品价格" value="<?php if(isset($info)) echo $info['price'];?>"/>
            </div>
        </div>
        <div class="form-group">
            <label for="sale_price" class="col-md-2 control-label">產品售價：</label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="sale_price" id="sale_price" placeholder="產品售價" value="<?php if(isset($info)) echo $info['sale_price'];?>"/>
            </div>
        </div>

        <div class="form-group">
            <label for="material" class="col-md-2 control-label">產品材質：</label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="material" id="material" placeholder="產品材質" value="<?php if(isset($info)) echo $info['material'];?>"/>
            </div>
        </div>
        <div class="form-group">
            <label for="producing_area" class="col-md-2 control-label">產品產地：</label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="producing_area" id="sale_material" placeholder="產品產地" value="<?php if(isset($info)) echo $info['producing_area'];?>"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label">產品尺寸：</label>
            <div class="col-md-8">
                <?php foreach($need['s'] as $v) :?>
                <label>
                    <input type="checkbox" name="size[]" value="{$v.id}" <?php if(in_array($v['id'],$info['size'])) echo 'checked="checked"'; ?> /> <span style="font-size:14pt;">{$v.name}　</span>
                </label>
                <?php endforeach;?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label">產品主圖：</label>
            <div class="col-md-8">

                <?php if($info['big_img']):?>
                    <div class="upimg_multi" style="display:inline-block;">
                        <input type="hidden" name="big_img" class="color_dir" value="{$info.big_img}" />
                        <span class="add_multi">+</span>
                        <input type="file">
                        <a class="del_multi">x</a>
                        <img src="{$info.big_img}" class="img-thumbnail" style="display:block;">
                    </div>
                <?php else:?>
                    <div class="upimg_multi" style="display:inline-block;">
                        <input type="hidden" name="big_img" class="color_dir" value="" />
                        <span class="add_multi">+</span>
                        <input type="file">
                        <a class="del_multi">x</a>
                        <img src="" class="img-thumbnail">
                    </div>
                <?php endif;?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label">產品顏色：</label>
            <div class="col-md-8">
                <?php foreach($need['c'] as $k => $v) :?>
                <div class="checkbox">
                    <div class="text-left">
                        <input type="checkbox" name="color[]" value="{$v.id}" <?php if(in_array($v['id'],$info['color'])) echo 'checked="checked"'; ?> /> <span style="font-size:14pt;">{$v.name}　</span>
                    </div>
                        <?php if(isset($info)) :?>
                            <?php foreach($info['color_img'][$v['id']] as $kk => $vv) :?>
                                <div class="upimg_multi" style="display:inline-block;">
                                    <input type="hidden" name="color[_{$v.id}][]" class="color_dir" value="{$vv.val}" />
                                    <span class="add_multi">+</span>
                                    <input type="file">
                                    <a class="del_multi">x</a>
                                    <img src="{$vv.url}" class="img-thumbnail" style="display:block;">
                                </div>
                            <?php endforeach;?>
                            <?php for($i = count($info['color_img'][$v['id']]); $i <= 7;$i++) : ?>
                            <div class="upimg_multi" style="display:inline-block;">
                                <input type="hidden" name="color[_{$v.id}][]" class="color_dir" value="" />
                                <span class="add_multi">+</span>
                                <input type="file">
                                <a class="del_multi">x</a>
                                <img src="" class="img-thumbnail">
                            </div>
                            <?php endfor;?>
                        <?php else : ?>
                            <?php for($i=0;$i<=7;$i++) : ?>
                            <div class="upimg_multi" style="display:inline-block;">
                                <input type="hidden" name="color[_{$v.id}][]" class="color_dir" value="" />
                                <span class="add_multi">+</span>
                                <input type="file">
                                <a class="del_multi">x</a>
                                <img src="" class="img-thumbnail">
                            </div>
                            <?php endfor;?>
                        <?php endif;?>

                </div>
                <?php endforeach;?>
            </div>
        </div>


        <div class="form-group">
            <label class="col-md-2 control-label">產品簡介：</label>
            <div class="col-md-8">
                <textarea class="form-control" name="info" rows="10" placeholder="產品簡介">{$info.info}</textarea>
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
                    $('input[type="submit"]').attr('disabled','disabled').val('等待图片上传完毕...');
                    _this.prev().html('...');
                },
                success     : function(res) {
                    if(typeof res.file_id != undefined) {
                        _this.prev().html('+');
                        _this.parent().find('.img-thumbnail').attr('src',res.url).fadeIn();
//                        _this.parent().next().show();
                        _this.parent().find('.color_dir').val(res.url);
                    }else {
                        $.dialog_show('e',res);
                    }
                    $('input[type="submit"]').removeAttr('disabled').val('提交');
                }
            });
        });

        $('.del_multi').on('click',function() {
            var _this       = $(this);

            _this.hide();
            _this.parent().find('img').hide();
            _this.parent().find('input[type="hidden"]').val('');
        });

        $('.img-thumbnail').on('mouseover',function() {
            $(this).parent().find('.del_multi').show();
        });

    </script>
</block>