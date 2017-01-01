<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/28
 * Time: 14:03
 */
namespace Home\Controller;
use Home\Model\TimeModel;

class TimeController extends  BaseController{
    public static function  getTimeInfo(){
        $Ret = TimeModel::getTimeInfo();
        if(!$Ret){
            return false;
        }
        $startTime = $Ret['start'];
        $endTime = $Ret['end'];
        $nowTime = time();
        if($startTime > $nowTime || $endTime < $nowTime){
            return false;
        }
        return $endTime - $nowTime;
    }
}