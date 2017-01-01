<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/27
 * Time: 16:23
 */
namespace Home\Controller;
use Home\Model\BuyCarModel;
class BuyCarController extends BaseController{
    public  static function getBuyCarInfoByUser($user){
        if(!$user){
            return false;
        }

        $condition = array(
            'user'=>$user,
        );
        $buyInfoRet = BuyCarModel::getBuyCarInfo($condition);
        if($buyInfoRet){
           return $buyInfoRet;
        }
        return false;
    }
}