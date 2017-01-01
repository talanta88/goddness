<extend name="Base/iframeBase" />
<link href="__CSS__/bootstrap.min.css" rel="stylesheet">
<block name="title">
    订单列表
</block>
<block name="info">
    <div class="form-group">
        <label for="start"> 订单查询：</label>
    </div>
    <div class="form-group col-lg-offset-1">
        <form class="form-inline" method="post" action="{:U('Order/index')}">
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
                <th>訂單號</th>
                <th>訂單時間</th>
                <th>訂單金額</th>
                <th>配送方式</th>
                <th>邮费</th>
                <th>收貨地址</th>
                <th>服務編號5碼</th>
                <th>收貨人</th>
                <th>FB聯繫人</th>
                <th>手機</th>
                <th>電子郵件</th>
                <th>備註</th>
                <th>支付状态</th>
                <th>訂單状态</th>
                <th>發貨状态</th>
                <th>操作</th>
            </tr>
        <thead>
        <tbody>
        <?php foreach($order_list as $key=>$val):?>
        <tr>
            <td>{$val.order_id}</td>
            <td><a href="{:U('Order/detail',array('order_id'=>$val['order_id'],'order_sn'=>$val['order_sn']))}" target="_blank">{$val.order_sn}</a></td>
            <td>{$val.order_time}</td>
            <td class="col-md-1">
                <span >總金額：{$val.goods_amount}</span><br/>
                <span class="text-left">應付：{$val.order_amount}</span><br/>
                <span class="text-left">折扣：{$val.discount_money}</span><br/>
                <span class="text-left">滿減：{$val.fullcut_money}</span>
            </td>
            <td>{$val.shipping_name}</td>
            <td>{$val.shipping_fee}</td>
            <td>{$val.post_num_text}{$val.address}</td>
            <td>{$val.house_no}</td>
            <td>{$val.consignee}</td>
            <td>{$val.fb_consignee}</td>
            <td>{$val.mobile}</td>
            <td>{$val.email}</td>
            <td>{$val.postscript}</td>
            <td>
                <span>{$val.pay_text}</span>
            </td>
            <td>
                <span>{$val.order_text}</span>
            </td>
            <td>
                <span>{$val.shipping_text}</span>
            </td>
           <td>
               <?php if($val['pay_text']!='已支付'):?>
                    <button type="button" class="btn btn-warning btn-xs confirm_pay" data-id="{$val.order_id}" style="margin-top:10px;">確認付款</button><br/>
               <?php else:?>
                    <button type="button" class="btn btn-warning btn-xs confirm_no_pay" data-id="{$val.order_id}" style="margin-top:10px;">確認未付款</button><br/>
               <?php endif;?>

               <?php if($val['shipping_text']!='已發貨'):?>
                    <button type="button" class="btn btn-success btn-xs confirm_shipping" data-id="{$val.order_id}" style="margin-top:10px;">確認發貨</button><br/>
               <?php else:?>
                    <button type="button" class="btn btn-success btn-xs confirm_no_shipping" data-id="{$val.order_id}" style="margin-top:10px;">確認未發貨</button><br/>
               <?php endif;?>

               <?php if($val['order_text']!='已取消'):?>
                    <button type="button" class="btn btn-danger btn-xs confirm_cancel" data-id="{$val.order_id}" style="margin-top:10px;">取消訂單</button><br/>
               <?php else:?>
                    <button type="button" class="btn btn-danger btn-xs confirm_no_cancel" data-id="{$val.order_id}" style="margin-top:10px;">撤回取消</button><br/>
                <?php endif;?>
           </td>
        </tr>
        <?php endforeach;?>
        <tr>
            <td colspan="17"><?php echo $show_page; ?></td>
        </tr>
        </tbody>
    </table>
    <script type="text/javascript">
        $('.confirm_pay').click(function(){
            var _this   = $(this);
            var confirmPayUrl = '__URL__/confirm_pay';
            var id     = _this.attr('data-id');
            $.get(confirmPayUrl,{id:id},function(res) {
                    $.dialog_show('e',res.data);
                if(res.status){
                    window.location.reload();
                }
            });
        })
        $('.confirm_no_pay').click(function(){
            var _this   = $(this);
            var confirmPayUrl = '__URL__/confirm_no_pay';
            var id     = _this.attr('data-id');
            $.get(confirmPayUrl,{id:id},function(res) {
                    $.dialog_show('e',res.data);
                if(res.status){
                    window.location.reload();
                }
            });
        })
        $('.confirm_shipping').click(function(){
            var _this   = $(this);
            var confirmPayUrl = '__URL__/confirm_shipping';
            var id     = _this.attr('data-id');
            $.get(confirmPayUrl,{id:id},function(res) {
                $.dialog_show('e',res.data);
                if(res.status){
                    window.location.reload();
                }
            });
        })
        $('.confirm_no_shipping').click(function(){
            var _this   = $(this);
            var confirmPayUrl = '__URL__/confirm_no_shipping';
            var id     = _this.attr('data-id');
            $.get(confirmPayUrl,{id:id},function(res) {
                $.dialog_show('e',res.data);
                if(res.status){
                    window.location.reload();
                }
            });
        })
        $('.confirm_cancel').click(function(){
            var _this   = $(this);
            var confirmPayUrl = '__URL__/confirm_cancel';
            var id     = _this.attr('data-id');
            $.get(confirmPayUrl,{id:id},function(res) {
                $.dialog_show('e',res.data);
                if(res.status){
                    window.location.reload();
                }
            });
        })
        $('.confirm_no_cancel').click(function(){
            var _this   = $(this);
            var confirmPayUrl = '__URL__/confirm_no_cancel';
            var id     = _this.attr('data-id');
            $.get(confirmPayUrl,{id:id},function(res) {
                $.dialog_show('e',res.data);
                if(res.status){
                    window.location.reload();
                }
            });
        })
        /*$('.del').on('click',function() {
            var _this   = $(this);
            var id      = _this.attr('val');
            var bid     = _this.attr('bid');
            $.get("__URL__/del",{id:id,bid:bid},function(res) {
                if(res.status == 0){
                    $.dialog_show('e',res.data);
                    return false;
                }else {
                    _this.parent().parent().remove();
                }
            });
        });*/
    </script>
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
            var url = "{:U('Order/downOrderInfo',array('startTime'=>$start_time,'endTime'=>$end_time,'pay_status'=>$pay_status,'order_status'=>$order_status,'shipping_status'=>$shipping_status))}";
            window.open(url);
        })
    </script>
</block>