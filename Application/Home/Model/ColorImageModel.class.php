<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/28
 * Time: 11:31
 */
namespace Home\Model;

class ColorImageModel extends BaseModel{
    protected static function getPrimaryKey(){
        return 'id';
    }

    protected static function getTableNames(){
        return 'colorimg';
    }

    public static function getColorImagesInfo($condition){
        if(!$condition){
            return false;
        }
        $Ret = D(self::getTableNames())->where($condition)->order('id asc')->select();
        if($Ret){
            return $Ret;
        }
        return false;
    }
}