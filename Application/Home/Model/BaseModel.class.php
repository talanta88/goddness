<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/2/27
 * Time: 18:08
 */
namespace Home\Model;
use Think\Model;

class BaseModel extends Model{

    public static function testLastSql(){
        return M()->getLastSql();
    }
}