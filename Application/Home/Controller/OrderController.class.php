<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/27
 * Time: 14:40
 */
namespace Home\Controller;
use Home\Model\DiscountNoModel;
use Home\Model\DiscountModel;
use Home\Model\OrderItemModel;
use Home\Model\OrdersModel;

class OrderController extends BaseController{
    /**
     * @var int
     *
     * 訂單最大數值
     */
    private static $lastNum = 99999;

    private static $prifixConfig = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

    public static function createOrderSn(){
        $lastIdRet = OrdersModel::getOrdersLastId();
        $lastId = $lastIdRet['lastid'];
        if(!$lastId){
            $orderSn = self::$prifixConfig[0].'00001';
            return $orderSn;
        }
        $lastOrderInfo = OrdersModel::getOrderInfoByOrderId($lastId);
        if(!$lastOrderInfo){
            return false;
        }
        $lastOrderSn = $lastOrderInfo['order_sn'];
        $lastPrefix = substr($lastOrderSn,0,1);
        $lastNum = intval(substr($lastOrderSn,1,5));
        if($lastNum < self::$lastNum){
            $orderSn = $lastPrefix.sprintf('%05s',++$lastNum);
            return $orderSn;
        }

        $lastKey = array_keys(self::$prifixConfig,$lastPrefix);
        $lastKey = $lastKey + 1;
        return self::$prifixConfig[$lastKey].sprintf('%05s',++$lastNum);
    }

    public static function addOrdersInfo($baseInfo,$orderItem){
        if(!($baseInfo && $orderItem)){
            return false;
        }
    /*    $orderInfo = array(
            'goods_amount' => '0.00',//商品總金額
            'fullcut_id'=>'0',//滿減規則
            'fullcut_money'=> '0.00',//滿減金額
            'order_amount'=> '0.00',//應付款金額
        );

        //獲取優惠卷信息
        $discountMoney = 0;
        $discountNo = $baseInfo['discount_no'];
        $discountInfo = DiscountNoModel::getDiscountInfoByNo($discountNo);
        if(!$discountInfo)
        {
            $startTime = $discountInfo['start'];
            $endTime = $discountInfo['end'];
            $currTime = time();
            if($currTime >= $startTime && $currTime <= $endTime)
            {
                $discountMoney = $discountInfo['price'];
            }else{
                $discountMoney = 0;
            }
        }

        //優惠卷號
        if(!$discountNo)
        {
            $orderInfo['discount_no'] = $discountNo;
        }
        //優惠卷金額
        $orderInfo['discount_money'] = $discountMoney;

        //商品總數
        $goodsTotal = 0;

        //商品總金額
        $goodsTotalMoney = 0;

        //訂單信息
        $itemInfo = array();
        //品項id
        $pids = array();
        foreach($buyCarInfo AS $key=>$val ){
            $pid = $val['pid'];//一級品項id
            if(!in_array($pid,array_values($pids))){
                array_push($pids,$pid);
            }
            $itemInfo[] = json_decode($val['info']);
            $goodsTotalMoney += $itemInfo['price'];
        }

        $orderInfo['goods_amount'] = $goodsTotalMoney;

        //折扣信息
        $fullCutInfo = array();
        $fullCutIds = array_values($pids);
        foreach($fullCutIds as $key=>$val){
            $condition = array('bid'=>$val);
            if(array_key_exists($val,$fullCutInfo)){
                $fullCutInfo[$val][] = DiscountModel::getDiscountInfo($condition);
            }else{
                $fullCutInfo[$val] = DiscountModel::getDiscountInfo($condition);
            }
        }
        //print_r($fullCutInfo);
        //應付金額
        $orderAmount = $goodsTotalMoney - $discountMoney;
        $orderInfo['order_amount'] = $orderAmount;*/
        $orderInfo['order_sn'] = self::createOrderSn();
        $orderInfo['order_status'] = OrdersModel::ORDER_DEFAULT;
        $orderInfo['shipping_status'] = OrdersModel::SHIPPING_DEFAULT;
        $orderInfo['pay_status'] = OrdersModel::PAY_NO;
        $orderInfo['order_time'] = Date('Y-m-d H:i:s',time());
        $orderInfo = array_merge($baseInfo,$orderInfo);

        //生成訂單主記錄
        $lastInsertId = OrdersModel::addOrderInfo($orderInfo);
        if(!$lastInsertId){
            return false;
        }
        //訂單明細
        $orderItemData = array();
        foreach($orderItem as $key=>$val){
            $item = array(
                'order_id'=> $lastInsertId,
                'goods_id'=> $val->goods_id,
                'first_img'=> $val->first_img,
                'goods_color'=> $val->color,
                'goods_size'=> $val->size,
                'goods_price'=>$val->price,
                'goods_name'=>$val->pro_name,
                'banner_id'=>$val->pid,
                'goods_number'=>$val->num,
                'status'=>OrderItemModel::NORMAL_STATUS,
            );
            array_push( $orderItemData ,$item );
        }
        $addOrderItemRet = OrderItemModel::addOrderItems($orderItemData);
        if($addOrderItemRet){
            return $orderInfo['order_sn'];
        }
        return false;
    }
}