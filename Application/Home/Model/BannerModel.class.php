<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/28
 * Time: 20:32
 */
namespace Home\Model;
class BannerModel extends  BaseModel{
    protected static function getPrimaryKey(){
        return 'id';
    }

    protected static function getTableNames(){
        return 'banner';
    }

    public static function getBannerInfoById($bid){
        if(!$bid){
            return false;
        }
        $condition = array(
            'id'=>$bid,
        );
        $Ret = D(self::getTableNames())->where($condition)->select();
        if($Ret){
            return $Ret[0];
        }
        return false;
    }
}