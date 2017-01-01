<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/27
 * Time: 15:35
 */
namespace Home\Model;

class OrderItemModel extends BaseModel
{
    /**
     * @var integer
     *  訂單明細狀態 默認異常
     */
    const DEFAULT_STATUS = 0;

    /**
     * @var integer
     * 訂單明細狀態 正常
     */
    const NORMAL_STATUS = 1;

    public static $sizeConfig = array('xxs' => 0, 'xs' => 1, 's' => 2, 'm' => 3, 'l' => 4, 'xl' => 5, 'xxl' => 6);
    protected static function getPrimaryKey()
    {
        return 'id';
    }

    protected static function getTableNames()
    {
        return 'order_item';
    }

    private static $orderItemStatusConfig = array(
        self::NORMAL_STATUS => '正常',
        self::DEFAULT_STATUS => '異常',
    );

    public static function getOrderItemStatusConfig($status)
    {
        return self::$orderItemStatusConfig[$status];
    }

    public static function addOrderItems($orderItem)
    {
        if (!$orderItem) {
            return false;
        }
        $Ret = D(self::getTableNames())->addAll($orderItem);
        if ($Ret) {
            return $Ret;
        }
        return false;
    }

    public static function getOrderItemInfo($condition)
    {
        if (!$condition) {
            return false;
        }
        $Ret = D(self::getTableNames())->where($condition)->select();
        if ($Ret) {
            return $Ret;
        }
        return false;
    }

    public static function getOrderItems($condition)
    {
        $whereStr = ' WHERE 1 ';
        if($condition){
            foreach($condition as $key=>$val){
                $whereStr .= ' AND '.$key.' in ('.implode(',',$val).')';
            }
        }
        $sql = "SELECT gg.self_product_id,goi.goods_id,goi.goods_color,goi.goods_size,goi.goods_name
                FROM god_order_item AS goi
                LEFT JOIN
                god_goods AS gg ON gg.id = goi.goods_id ".$whereStr."
                GROUP BY gg.self_product_id,goi.goods_id,goi.goods_color,goi.goods_size";
        $ret = M()->query($sql);
        return $ret;
    }
}