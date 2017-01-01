<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/28
 * Time: 13:59
 */
namespace Home\Model;
class TimeModel extends BaseModel{
    protected static function getPrimaryKey(){
        return 'id';
    }

    protected static function getTableNames(){
        return 'time';
    }

    public static function getTimeInfo(){
        $Ret = D(self::getTableNames())->select();
        if($Ret){
            return $Ret[0];
        }
        return false;
    }
}