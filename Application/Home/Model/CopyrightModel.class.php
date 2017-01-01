<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/3/5
 * Time: 23:26
 */
namespace Home\Model;
class CopyrightModel extends BaseModel{
    protected static function getPrimaryKey(){
        return 'id';
    }

    protected static function getTableNames(){
        return 'copyright';
    }

    public static function getCopyrightAllInfo(){
        $Ret = D(self::getTableNames())->limit(1)->select();
        if($Ret){
            return $Ret[0];
        }
        return false;
    }

    public static function updCopyrightInfo($data){
        if(!$data){
            return false;
        }
        $Ret = D(self::getTableNames())->save($data);
        if($Ret){
            return $Ret;
        }
        return false;
    }

    public static function addCopyrightInfo($data){
        if(!$data){
            return false;
        }
        $Ret = D(self::getTableNames())->add($data);
        if($Ret){
            return $Ret;
        }
        return false;
    }
}