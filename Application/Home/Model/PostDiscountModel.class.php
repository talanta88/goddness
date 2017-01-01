<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/3/5
 * Time: 0:02
 */
namespace Home\Model;
class PostDiscountModel extends BaseModel{
    const STATUS_OPEN = 1;
    const STATUS_CLOSE = 0;
    protected static function getPrimaryKey(){
        return 'id';
    }

    protected static function getTableNames(){
        return 'post_discount';
    }

    public static function getPostDiscountInfo($condition){
        if(!$condition){
            return false;
        }

        $Ret = D(self::getTableNames())->where($condition)->select();
        if(!$Ret){
            return false;
        }
        return $Ret;
    }

    public static function getPostDiscountAllInfo(){
        $condition['status'] = self::STATUS_OPEN;
        $Ret = D(self::getTableNames())->field('*')->where($condition)->select();
        if(!$Ret){
            return false;
        }
        return $Ret;
    }
    public static function addPostDiscountInfo($data){
        if(!$data){
            return false;
        }
        $Ret = D(self::getTableNames())->add($data);
        if($Ret){
            return $Ret;
        }
        return false;
    }
    public static function updPostDiscountInfo($updData){
        if(!$updData){
            return false;
        }
        $Ret = D(self::getTableNames())->save($updData);
        if($Ret){
            return $Ret;
        }
        return false;
    }

    public static function delPostDiscountInfo($id){
        if(!$id){
            return false;
        }
        $updData = array(
            'id'=>$id,
            'update_time'=>Date('Y-m-d H:i:s'),
            'status'=>self::STATUS_CLOSE,
        );
        $Ret = self::updPostDiscountInfo($updData);
        return $Ret;
    }
}