<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/27
 * Time: 21:35
 */
namespace Home\Model;

class DiscountModel extends BaseModel{
    protected static function getPrimaryKey(){
        return 'id';
    }

    protected static function getTableNames(){
        return 'discount';
    }

    public static function getDiscountInfo($condition){
        if(!$condition){
            return false;
        }
        $Ret = D(self::getTableNames())->where($condition)->select();
        if($Ret){
            return $Ret;
        }
        return false;
    }

    public static function getDiscountAllInfo(){
        $Ret = D(self::getTableNames())->select();
        if($Ret){
            return $Ret;
        }
        return false;
    }
}