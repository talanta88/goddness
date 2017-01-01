<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/28
 * Time: 11:36
 */
namespace Home\Model;
class ColorModel extends BaseModel{
    protected static function getPrimaryKey(){
        return 'id';
    }

    protected static function getTableNames(){
        return 'color';
    }

    public static function getColorIdByName($color_name){
        if(!$color_name){
            return false;
        }
        $condition = array(
            'name'=>array('in',$color_name),
        );

        $Ret = D(self::getTableNames())->field(self::getPrimaryKey())->where($condition)->select();
        if($Ret){
            return $Ret;
        }
        return false;
    }

    public static function getColorInfo(){
        $ret = D(self::getTableNames())->field('name')->select();
        if($ret){
            return $ret;
        }
        return false;
    }
}