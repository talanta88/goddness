<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/3/19
 * Time: 19:22
 */
namespace Admin\Controller;
use Home\Model\SalesPromotionModel;

class SalespromotionController extends CommonController {

    public function index(){
        $ret = SalesPromotionModel::getOneInfo();
        $this->assign('info',$ret);
        $this->display();
    }

    public function update(){
        $name = I('post.title');
        $id = I('post.id');
        if(!$id){
            //新增
            $data = array(
                'title'=>self::strFalter($name),
                'add_time'=>time(),
            );
            $ret = SalesPromotionModel::addInfo($data);
        }else{
            //修改
            $data = array(
                'id'=>$id,
                'title'=>self::strFalter($name),
                'upd_time'=>time(),
            );
            $ret = SalesPromotionModel::saveInfo($data);
        }
        if(!$ret){
            $this->error('條幅修改失敗');
        }else{
            $this->success('條幅修改成功');
        }

        $ret = SalesPromotionModel::getOneInfo();
        $this->assign('info',$ret);
        $this->display('index');
    }
}