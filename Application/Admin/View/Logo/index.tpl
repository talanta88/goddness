<extend name="Base/iframeBase" />
<block name="title">
    Logo管理
</block>
<block name="info">
    <?php if(empty($logo)) :?>
        <p>{$str.mes_str}</p>
    <?php else : ?>
        <a href="{$logo}" target="_blank"><img src="{$logo}" style="width:300px;" class="img-thumbnail img-responsive"/></a>
    <?php endif; ?>
    <form class="form-horizontal" method="post" action="{:U('Logo/add')}">
        <div class="form-group">
            <div class="col-md-12">
                <input type="file" class="form-control" name="logo_img">
                <div class="progress" style="display: none;">
                    <div class="progress-bar" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100" style="width: 5%"></div>
                </div>
                <div class="file_img">
                    <img src="" class="pic_show" width="150px;" style="display: none;">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-12">
                <input type="submit" class="btn btn-primary btn-block submit" value="{$str.btn_str}">
            </div>
        </div>
    </form>
    <script type="application/javascript">
        $(function() {
            $('input[name="logo_img"]').on('change',function() {
                $.img_upload(this,'logo');
            });

            $('.submit').on('click',function() {
                var logo_val        = $('input[name="logo"]').val();
                if(logo_val == undefined || logo_val.length == 0)
                    $.dialog_show('e','请上传图片');
            });
        });
    </script>
</block>
