<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/27
 * Time: 16:20
 */
namespace Home\Model;
use Home\Model;
class BuyCarModel extends BaseModel{
    protected static function getPrimaryKey(){
        return 'id';
    }

    protected static function getTableNames(){
        return 'buycar';
    }

    public static function getBuyCarInfo($condition){
        if(!$condition){
            return false;
        }
        $Ret = D(self::getTableNames())->where($condition)->select();
        if($Ret){
            return $Ret;
        }
        return false;
    }
}