<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/28
 * Time: 17:59
 */
namespace Admin\Common;
use Home\Model\OrdersModel;

function shippingStatusText($shipping_status){
    return OrdersModel::getShippingStatusConfig($shipping_status);
}

function orderStatusText($order_status){
    return OrdersModel::getOrderStatusConfig($order_status);
}

function payStatusText($pay_status){
    return OrdersModel::getPayStatusConfig($pay_status);
}