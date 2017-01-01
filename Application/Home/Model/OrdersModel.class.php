<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/27
 * Time: 14:19
 */
namespace Home\Model;

class OrdersModel extends BaseModel{
    /**
     * @var integer
     * 訂單狀態 未確認
     */
    const ORDER_DEFAULT = 0;

    /**
     * @var integer
     * 訂單狀態 已確認
     */
    const ORDER_CONFIRM = 1;

    /**
     * @var integer
     * 訂單狀態 已取消
     */
    const ORDER_CANCEL = 2;

    /**
     * @var integer
     * 訂單狀態 無效
     */
    const ORDER_INVALID = 3;

    /**
     * @var integer
     * 訂單狀態 退貨
     */
    const ORDER_BACK = 4;

    /**
     * @var integer
     * 發貨狀態 未發貨
     */
    const SHIPPING_DEFAULT = 0;

    /**
     * @var integer
     * 發貨狀態 已發貨
     */
    const SHIPPING_DELIVERY = 1;

    /**
     * @var integer
     * 發貨狀態 已收貨
     */
    const SHIPPING_CONFIRM = 2;

    /**
     * @var integer
     * 發貨狀態 退貨
     */
    const SHIPPING_BACK = 3;

    /**
     * @var integer
     * 支付狀態 未支付
     */
    const PAY_NO = 0;

    /**
     * @var integer
     * 支付狀態 已支付
     */
    const PAY_YES = 1;

    protected static function getPrimaryKey(){
        return 'order_id';
    }

    protected static function getTableNames(){
        return 'orders';
    }

    public  static $shippingStatusConfig = array(
        self::SHIPPING_DEFAULT=>'未發貨',
        self::SHIPPING_DELIVERY=>'已發貨',
        /*self::SHIPPING_CONFIRM=>'已收貨',
        self::SHIPPING_BACK=>'退貨',*/
    );
    public  static function getShippingStatusConfig($shipping_status){
        return self::$shippingStatusConfig[$shipping_status];
    }

    public static $orderStatusConfig = array(
        self::ORDER_DEFAULT=>'未確認',
        self::ORDER_CONFIRM=>'已確認',
        self::ORDER_CANCEL=>'已取消',
        /*self::ORDER_INVALID=>'無效',
        self::ORDER_BACK=>'退貨',*/
    );
    public  static function getOrderStatusConfig($order_status){
        return self::$orderStatusConfig[$order_status];
    }

    public  static $payStatusConfig = array(
        self::PAY_NO=>'未支付',
        self::PAY_YES=>'已支付',
    );
    public static function getPayStatusConfig($pay_status){
        return self::$payStatusConfig[$pay_status];
    }

    public static function addOrderInfo($order_info){
        if(!$order_info){
            return false;
        }
        $Ret = D(self::getTableNames())->add($order_info);
        if($Ret){
            return $Ret;
        }
        return false;
    }

    public static function getOrderInfo($condition){
        $Ret = D(self::getTableNames())->where($condition)->select();
        if($Ret){
            return $Ret;
        }
        return false;
    }
    public static function getOrdersLastId(){
        $fields = 'MAX('.self::getPrimaryKey().') AS lastId';
        $Ret = D(self::getTableNames())->field($fields)->select();
        if($Ret){
           return $Ret[0];
        }
        return false;
    }
    public static function getOrderInfoByOrderId($order_id){
        if(!$order_id){
            return false;
        }
        $key = self::getPrimaryKey();
        $conditon = array(
            $key=>$order_id,
        );
        $Ret = D(self::getTableNames())->where($conditon)->select();
        if($Ret){
            return  $Ret[0];
        }
        return false;
    }
    public static function getOrderCount($condition){
        $countRet = D(self::getTableNames())->where($condition)->count();
        if($countRet){
            return $countRet;
        }
        return false;
    }
    public static function getOrderListPage($condition = array(), $offset=0, $limit = 20){
        $listRet = D(self::getTableNames())
            ->where($condition)
            ->order('order_id desc')
            ->limit($offset.','.$limit)->select();
        if($listRet){
            return $listRet;
        }
        return false;
    }

    public static function updateOrderStatus($condition,$data){
        if(!($condition && $data)){
            return false;
        }

        $updRet = D(self::getTableNames())->where($condition)->save($data);
        if($updRet){
            return $updRet;
        }
        return false;
    }
    public static function updateData($data){
        if(!($data)){
            return false;
        }
        $updRet = D(self::getTableNames())->save($data);
        if($updRet){
            return $updRet;
        }
        return false;
    }

    public static function getGoodsOrderList($condition = array(), $offset=0, $limit = 20){
        $orderList = M(self::getTableNames())
                ->field('SQL_CALC_FOUND_ROWS god_order_item.goods_name,
                        god_order_item.goods_color,
                        god_order_item.goods_size,
                        god_goods.material,
                        god_goods.producing_area,
                        god_goods.sale_price,
                        sum(god_order_item.goods_number) as num')
                ->join('god_order_item ON god_order_item.order_id = god_orders.order_id')
                ->join('god_goods ON god_goods.id = god_order_item.goods_id')
                ->where($condition)
                ->group('god_order_item.goods_name,
                        god_order_item.goods_color,
                        god_order_item.goods_size')
                ->limit($offset.','.$limit)
                ->select();
        $count = M()->query("SELECT FOUND_ROWS() as count");
        if($orderList){
            return array(
                'list'=>$orderList,
                'count'=>$count[0]['count']
            );
        }
        return false;
    }
    public static function getAllGoodsOrderList($condition = array()){
        $orderList = M(self::getTableNames())
            ->field('SQL_CALC_FOUND_ROWS god_order_item.goods_name,
                        god_order_item.goods_color,
                        god_order_item.goods_size,
                        god_goods.material,
                        god_goods.producing_area,
                        god_goods.sale_price,
                        sum(god_order_item.goods_number) as num')
            ->join('god_order_item ON god_order_item.order_id = god_orders.order_id')
            ->join('god_goods ON god_goods.id = god_order_item.goods_id')
            ->where($condition)
            ->group('god_order_item.goods_name,
                        god_order_item.goods_color,
                        god_order_item.goods_size')
            ->select();
        return $orderList;
    }
}