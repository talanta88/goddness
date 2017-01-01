<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/3/5
 * Time: 8:07
 */
namespace Admin\Controller;
use Home\Model\PostDiscountModel;

class PostdiscountController extends  CommonController{
    public function index(){
        $postList = PostDiscountModel::getPostDiscountAllInfo();
        $this->assign('list',$postList);
        $this->display();
    }

    public function delPost(){
        $id = I('get.id','','trim');
        if(!$id){
            $this->error('參數有誤');
        }
        $Ret = PostDiscountModel::delPostDiscountInfo($id);
        if($Ret){
            $this->ajaxReturn(array('t' => 1,'i' => '关闭成功',data=>$Ret));
        }
        $this->ajaxReturn(array('t' => 0,'i' => '关闭失败'));
    }

    public function addPost(){
        $rule_money = I('get.rule_money','','trim');
        $discount_money = I('get.discount_money','','trim');
        if(!($rule_money && $discount_money)){
            $this->ajaxReturn(array('t' => 0,'i' => '參數有誤'));
        }
        $data['rule_money'] = $rule_money;
        $data['discount_money'] = $discount_money;
        $data['create_time'] = Date('Y-m-d H:i:s',time());
        $data['status'] = PostDiscountModel::STATUS_OPEN;
        $Ret = PostDiscountModel::addPostDiscountInfo($data);
        if($Ret){
            $this->ajaxReturn(array('t' => 1,'i' => '新增郵費優惠成功',data=>$Ret));
        }
        $this->ajaxReturn(array('t' => 0,'i' => '新增郵費優惠失败'));
    }
}