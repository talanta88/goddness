<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/27
 * Time: 20:05
 */
namespace Home\Model;
class DiscountNoModel extends BaseModel{
    /**
     * 限一次
     */
    const ONE_TIME_TYPE = 1;
    /**
     * 多次
     */
    const MORE_TIME_TYPE = 2;

    const IS_USED_YES = 1;

    const IS_USED_NO = 0;

    protected static function getPrimaryKey(){
        return 'id';
    }

    protected static function getTableNames(){
        return 'discountno';
    }

    public static  function getDiscountInfoByNo($discount_no){
        if(!$discount_no){
            return false;
        }

        $condition = array(
            'no'=>$discount_no
        );
        $Ret = D(self::getTableNames())->where($condition)->select();
        if($Ret){
            return $Ret[0];
        }
        return false;
    }

    public static function updDiscountUsedById($id){
        if(!$id){
            return false;
        }
        $data = array(
            'id'=> $id,
            'is_used'=> self::IS_USED_YES
        );
        $ret = D(self::getTableNames())->save($data);
        if($ret){
            return $ret;
        }
        return false;
    }
}