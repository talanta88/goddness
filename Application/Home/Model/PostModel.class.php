<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/3/5
 * Time: 10:13
 */
namespace Home\Model;
class PostModel extends  BaseModel{
    protected static function getPrimaryKey(){
        return 'id';
    }
    protected static function getTableNames(){
        return 'post';
    }

    public static function getPostInfo($condition){
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