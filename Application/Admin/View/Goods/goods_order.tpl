<extend name="Base/iframeBase" />
<link href="__CSS__/bootstrap.min.css" rel="stylesheet">
<block name="title">
    商品订单列表
</block>
<block name="info">
    <div class="form-group">
        <label for="start"> 時間查询：</label>
    </div>
    <div class="form-group col-lg-offset-1">
        <form class="form-inline" method="post" action="{:U('Goods/getGoodsOrderInfo')}">
            <div class="form-group">
                <label for="start">从：</label>
                <input type="text" name="start" class="form-control" id="start" placeholder="請選擇開始時間" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="{$start_time}">
            </div>
            <div class="form-group">
                <label for="start">至：</label>
                <input type="text" name="end" class="form-control" id="end" placeholder="請選擇結束時間" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="{$end_time}">
            </div>
            <div class="form-group">
                <label for="start">訂單狀態：</label>
                <select class="form-group" name="order_status">
                    <option value="">--請選擇--</option>
                    <?php foreach($order_status_arr as $key=>$val):?>
                    <option value="{$key}" <?php if($order_status == $key):?>selected="selected"<?php endif;?>>{$val}</option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="form-group">
                <label for="start">支付狀態：</label>
                <select class="form-group" name="pay_status">
                    <option value="">--請選擇--</option>
                    <?php foreach($pay_status_arr as $key=>$val):?>
                    <option value="{$key}"<?php if($pay_status == $key):?>selected="selected"<?php endif;?>>{$val}</option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="form-group">
                <label for="start">發貨狀態：</label>
                <select class="form-group" name="shipping_status">
                    <option value="">--請選擇--</option>
                    <?php foreach($shipping_status_arr as $key=>$val):?>
                    <option value="{$key}" <?php if($shipping_status == $key):?>selected="selected"<?php endif;?>>{$val}</option>
                    <?php endforeach;?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary submit">查询</button>
            <button type="button" class="btn btn-primary loadData">导出</button>
        </form>
    </div>
    <div class="">
    <table class="table table-bordered table-hover">
        <thead style="font-size: 12px;">
            <tr>
                <th>ID</th>
                <th>品名</th>
                <th>顏色</th>
                <th>尺寸</th>
                <th>材質</th>
                <th>產地</th>
                <th>售價</th>
                <th>數量</th>
            </tr>
        <thead>
        <tbody>
        <?php foreach($goods_order_list as $key=>$val):?>
        <tr>
            <td>{$key+$offset+1}</td>
            <td>{$val.goods_name}</td>
            <td>{$val.goods_color}</td>
            <td>{$val.goods_size}</td>
            <td>{$val.material}</td>
            <td>{$val.producing_area}</td>
            <td>{$val.sale_price}</td>
            <td>{$val.num}</td>
        </tr>
        <?php endforeach;?>
        <tr>
            <td colspan="17"><?php echo $page; ?></td>
        </tr>
        </tbody>
    </table>
    <script type="application/javascript" src="__JS__/DatePicker/WdatePicker.js"></script>
    <script type="application/javascript">
        $('.submit').on('click',function() {
            var start  = $('#start').val(), end  = $('#end').val();
            /*if(start.length <= 0) {
                $.dialog_show('e', '請選擇開始時間');
                $('#start').focus();
                return false;
            }
            if(end.length <= 0){
                $.dialog_show('e','請選擇結束時間');
                $('#end').focus();
                return false;
            }*/
        });

        //导出列表数据
        $('.loadData').click(function(){
            var url = "{:U('Goods/downGoodsOrderInfo',array('start'=>$start_time,'end'=>$end_time,'pay_status'=>$pay_status,'order_status'=>$order_status,'shipping_status'=>$shipping_status))}";
            window.open(url);
        })
    </script>
</block>