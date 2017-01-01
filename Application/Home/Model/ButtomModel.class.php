<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/3/6
 * Time: 1:14
 */
namespace Home\Model;
class ButtomModel extends BaseModel{
    const BUTTOM_TITLE = 'buttom_title';
    const BUTTOM_INFO = 'buttom_info';

    const BUTTOM_TYPE_LEFT = 1;
    const BUTTOM_TYPE_CENTER = 2;
    const BUTTOM_TYPE_RIGHT = 3;

    const  BUTTOM_STATUS_YES=1;
    const  BUTTOM_STATUS_NO=0;
    public static  $typeConfig = array(
            self::BUTTOM_TYPE_LEFT=>'左邊欄',
            self::BUTTOM_TYPE_CENTER=>'中間欄',
            self::BUTTOM_TYPE_RIGHT=>'右邊欄',
    );
    public static function getTypeConfig(){
        return self::$typeConfig;
    }
    public static function getAllButtomTitle(){
        $condition['status'] = self::BUTTOM_STATUS_YES;
        $Ret = D(self::BUTTOM_TITLE)->where($condition)->order('id asc')->select();
        if(!$Ret){
            return false;
        }
        return $Ret;
    }

    public static function getButtomTitle($type){
        if(!$type){
            return false;
        }

        $condition['type'] = $type;
        $Ret = D(self::BUTTOM_TITLE)->where($condition)->select();
        if(!$Ret){
            return false;
        }
        return $Ret;
    }

    public static function getButtomTitleByWhere($condition){
        if(!$condition){
            return false;
        }
        $Ret = D(self::BUTTOM_TITLE)->where($condition)->select();
        if(!$Ret){
            return false;
        }
        return $Ret;
    }

    public static function updButtomTitle($data){
        if(!$data){
            return false;
        }

        $Ret = D(self::BUTTOM_TITLE)->save($data);
        if($Ret){
            return $Ret;
        }
        return false;
    }

    public static function addButtomTitle($data){
        if(!$data){
            return false;
        }
        $Ret = D(self::BUTTOM_TITLE)->add($data);
        if($Ret){
            return $Ret;
        }
        return false;
    }

    public static function getButtomInfo($type){
        if(!$type){
            return false;
        }
        $condition['type'] = $type;
        $condition['status'] = self::BUTTOM_STATUS_YES;
        $Ret = D(self::BUTTOM_INFO)->where($condition)->select();
        if(!$Ret){
            return false;
        }
        return $Ret;
    }

    public static function getAllButtomInfo(){
        $condition['status'] = self::BUTTOM_STATUS_YES;
        $Ret = D(self::BUTTOM_INFO)->where($condition)->order('id asc')->select();
        if(!$Ret){
            return false;
        }
        return $Ret;
    }

    public static function getButtomInfoByWhere($condition){
        if(!$condition){
            return false;
        }
        $Ret = D(self::BUTTOM_INFO)->where($condition)->select();
        if(!$Ret){
            return false;
        }
        return $Ret;
    }

    public static function updButtomInfo($data){
        if(!$data){
            return false;
        }

        $Ret = D(self::BUTTOM_INFO)->save($data);
        if($Ret){
            return $Ret;
        }
        return false;
    }

    public static function addButtomInfo($data){
        if(!$data){
            return false;
        }
        $Ret = D(self::BUTTOM_INFO)->add($data);
        if($Ret){
            return $Ret;
        }
        return false;
    }
}
