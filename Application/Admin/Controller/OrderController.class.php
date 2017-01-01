<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/28
 * Time: 14:34
 */
namespace Admin\Controller;
use Home\Model\OrdersModel;
use Home\Controller\AddressController;

class OrderController extends CommonController{
    public  function index(){
        $startTime = I('start');
        $endTime = I('end');
        $orderStatus = I('order_status');
        $shippingStatus = I('shipping_status');
        $payStatus = I('pay_status');

        $condition = array();
        if($startTime && $endTime){
            $condition['order_time'] = array('between',array($startTime,$endTime));
        }
        if($orderStatus!=''){
            $condition['order_status'] = $orderStatus;
        }
        if($shippingStatus!=''){
            $condition['shipping_status'] = $shippingStatus;
        }
        if($payStatus !=''){
            $condition['pay_status'] = $payStatus;
        }
        $orderListRet = OrdersController::getOrderInfoList($condition);
        foreach($orderListRet['listRet'] as $key=>$val){
            $orderListRet['listRet'][$key]['shipping_text'] =  OrdersModel::getShippingStatusConfig($val['shipping_status']);
            $orderListRet['listRet'][$key]['order_text'] =  OrdersModel::getOrderStatusConfig($val['order_status']);
            $orderListRet['listRet'][$key]['pay_text'] =  OrdersModel::getPayStatusConfig($val['pay_status']);
            $orderListRet['listRet'][$key]['post_num_text'] =  AddressController::getAddressById($val['city']);
        }
        $shippingStatusArr = OrdersModel::$shippingStatusConfig;
        $orderStatusArr = OrdersModel::$orderStatusConfig;
        $payStatusArr = OrdersModel::$payStatusConfig;
        $this->assign('shipping_status_arr',$shippingStatusArr);
        $this->assign('shipping_status',$shippingStatus);
        $this->assign('order_status_arr',$orderStatusArr);
        $this->assign('order_status',$orderStatus);
        $this->assign('pay_status_arr',$payStatusArr);
        $this->assign('pay_status',$payStatus);
        $this->assign('start_time',$startTime);
        $this->assign('end_time',$endTime);
        $this->assign('order_list',$orderListRet['listRet']);
        $this->assign('show_page',$orderListRet['page']);
        $this->display();
    }

    public function detail(){
        $orderId = I('get.order_id','','trim');
        $orderSn = I('get.order_sn','','trim');
        $orderItemInfo = OrdersController::getOrderItemInfoByOrderId($orderId);
        $this->assign('orderSn',$orderSn);
        $this->assign('orderItemList',$orderItemInfo);
        $this->display();
    }
    public  function downOrderInfo(){
        $startTime = I('get.startTime','','trim');
        $endTime = I('get.endTime','','trim');
        $pay_status = I('get.pay_status','','trim');
        $order_status = I('get.order_status','','trim');
        $shipping_status = I('get.shipping_status','','trim');
        $condition = array();
        if($pay_status!=''){
            $condition['pay_status'] = $pay_status;
        }

        if($order_status!=''){
            $condition['order_status'] = $order_status;
        }

        if($shipping_status!=''){
            $condition['shipping_status'] = $shipping_status;
        }

        if($startTime && $endTime){
            $condition ['order_time'] = array('between',array($startTime,$endTime));
        }

        if(!$startTime && $endTime){
            $condition ['order_time'] = array('elt',$endTime);
        }

        if($startTime && $endTime){
            $condition ['order_time'] = array('egt',$startTime);
        }
        OrdersController::downOrderInfo($condition);
    }


    public function confirm_pay(){
        $id = I('get.id');
        if(!$id){
            $this->error('参数错误');
        }
        $data = array(
            'order_id'=>$id,
            'pay_status'=>OrdersModel::PAY_YES,
            'order_status'=>OrdersModel::ORDER_CONFIRM,
        );
        $ret = OrdersModel::updateData($data);
        if($ret){
            $this->ajaxReturn(array('status' => 1, 'data' => '操作成功'), 'json');
        }
        $this->ajaxReturn(array('status' => 0, 'data' => '操作失败'), 'json');
    }
    public function confirm_no_pay(){
        $id = I('get.id');
        if(!$id){
            $this->error('参数错误');
        }
        $data = array(
            'order_id'=>$id,
            'pay_status'=>OrdersModel::PAY_NO,
            'order_status'=>OrdersModel::ORDER_DEFAULT,
        );
        $ret = OrdersModel::updateData($data);
        if($ret){
            $this->ajaxReturn(array('status' => 1, 'data' => '操作成功'), 'json');
        }
        $this->ajaxReturn(array('status' => 0, 'data' => '操作失败'), 'json');
    }

    public function confirm_shipping(){
        $id = I('get.id');
        if(!$id){
            $this->error('参数错误');
        }
        $data = array(
            'order_id'=>$id,
            'shipping_status'=>OrdersModel::SHIPPING_DELIVERY,
        );
        $ret = OrdersModel::updateData($data);
        if($ret){
            $this->ajaxReturn(array('status' => 1, 'data' => '操作成功'), 'json');
        }
        $this->ajaxReturn(array('status' => 0, 'data' => '操作失败'), 'json');
    }

    public function confirm_no_shipping(){
        $id = I('get.id');
        if(!$id){
            $this->error('参数错误');
        }
        $data = array(
            'order_id'=>$id,
            'shipping_status'=>OrdersModel::SHIPPING_DEFAULT,
        );
        $ret = OrdersModel::updateData($data);
        if($ret){
            $this->ajaxReturn(array('status' => 1, 'data' => '操作成功'), 'json');
        }
        $this->ajaxReturn(array('status' => 0, 'data' => '操作失败'), 'json');
    }

    public function confirm_cancel(){
        $id = I('get.id');
        if(!$id){
            $this->error('参数错误');
        }
        $data = array(
            'order_id'=>$id,
            'order_status'=>OrdersModel::ORDER_CANCEL,
        );
        $ret = OrdersModel::updateData($data);
        if($ret){
            $this->ajaxReturn(array('status' => 1, 'data' => '操作成功'), 'json');
        }
        $this->ajaxReturn(array('status' => 0, 'data' => '操作失败'), 'json');
    }

    public function confirm_no_cancel(){
        $id = I('get.id');
        if(!$id){
            $this->error('参数错误');
        }
        $data = array(
            'order_id'=>$id,
            'pay_status'=>OrdersModel::PAY_NO,
            'order_status'=>OrdersModel::ORDER_DEFAULT,
        );
        $ret = OrdersModel::updateData($data);
        if($ret){
            $this->ajaxReturn(array('status' => 1, 'data' => '操作成功'), 'json');
        }
        $this->ajaxReturn(array('status' => 0, 'data' => '操作失败'), 'json');
    }
}