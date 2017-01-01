<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/3/1
 * Time: 20:34
 */
namespace  Home\Model;

class AttentionModel extends BaseModel{
    const NORMAL_STATUS =1;
    const DELETE_STATUS =0;
    protected static function getPrimaryKey(){
        return 'id';
    }
    protected static function getTableNames(){
        return 'attention';
    }
    public static function getAllInfo(){
        $Ret = D(self::getTableNames())->where(array('status'=>1))->select();
        if($Ret){
            return $Ret;
        }
        return false;
    }
    public static function getOneInfoById($id){
        if(!$id){
            return false;
        }
        $condition = array(
            'id'=>$id
        );
        $Ret =  D(self::getTableNames())->where($condition)->select();
        if($Ret){
            return $Ret[0];
        }
        return false;
    }
    public static function saveInfo($data){
        if(!$data){
            return flase;
        }
        $Ret = D(self::getTableNames())->save($data);
        if($Ret){
            return $Ret;
        }
        return false;
    }
    public static function addInfo($data){
        if(!$data){
            return false;
        }
        $Ret = D(self::getTableNames())->add($data);
        if($Ret){
            return $Ret;
        }
        return false;
    }

    public static function getOneInfo(){
        $Ret = D(self::getTableNames())->where(array('status'=>1))->limit(1)->select();
        if($Ret){
            return $Ret[0];
        }
        return false;
    }
}