<extend name="Base/iframeBase" />
<link href="__CSS__/bootstrap.min.css" rel="stylesheet">
<block name="title">
    订单詳情[{$orderSn}]：
</block>
<block name="info">
    <div class="form-group">
        <table class="table table-bordered table-hover">
            <thead style="font-size: 12px;">
            <tr>
                <th>ID</th>
                <th>商品圖片</th>
                <th>商品名</th>
                <th>尺寸</th>
                <th>顏色</th>
                <th>商品数量</th>
                <th>品牌</th>
                <th>狀態</th>
            </tr>
            <thead>
            <tbody>
            <?php foreach($orderItemList as $key=>$val):?>
            <tr>
                <td>{$val.id}</td>
                <td><img src="{$val.first_img}" height="65px" width="65px"/> </td>
                <td><?php if($val['self_product_id']):?>{$val.self_product_id}.<?php endif;?>{$val.goods_name}</td>
                <td>{$val.goods_size}</td>
                <td>{$val.goods_color}</td>
                <td>{$val.goods_number}</td>
                <td>{$val.branner_name}</td>
                <td>{$val.status_text}</td>
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
</block>