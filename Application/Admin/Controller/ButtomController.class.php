<?php
/**
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/3/5
 * Time: 22:10
 */
namespace Admin\Controller;
use Home\Model\ButtomModel;

class ButtomController extends  CommonController{

    public function left(){
        $Ret = ButtomModel::getButtomInfo(ButtomModel::BUTTOM_TYPE_LEFT);
        $this->assign('list',$Ret);
        $this->display();
    }
    public function left_add(){
        $link_id = I('get.id','','trim');
        $data = array();
        if($link_id){
            $condition['id'] = $link_id;
            $data = ButtomModel::getButtomInfoByWhere($condition);
        }
        $this->assign('link',$data[0]);
        $this->display();
    }
    public function left_add_action(){
        $link_id = I('post.id','','trim');
        $link_name = I('post.link_name','','trim');
        $link_content = I('post.content','','trim');
        if(!$link_id) {
            $data = array(
                'link_name' => $link_name,
                'content' => self::strFalter($link_content),
                'type' => ButtomModel::BUTTOM_TYPE_LEFT,
                'status' => ButtomModel::BUTTOM_STATUS_YES,
                'create_time' => Date('Y-m-d H:i:s', time()),
            );
            ButtomModel::addButtomInfo($data);
        }else{
            $data  = array(
                'id'=>$link_id,
                'link_name'=>$link_name,
                'content'=>self::strFalter($link_content),
                'update_time'=>Date('Y-m-d H:i:s', time()),
            );
            ButtomModel::updButtomInfo($data);
        }
        $this->assign('link',$data);
        $this->display('left_add');
    }
    public function left_del(){
        $link_id = I('get.id','','trim');
        if(!$link_id){
            $this->ajaxReturn(array('i' => '参数有误', 't' => 0));
        }
        $data  = array(
            'id'=>$link_id,
            'status'=>ButtomModel::BUTTOM_STATUS_NO,
            'update_time'=>Date('Y-m-d',time()),
        );
       $Ret =  ButtomModel::updButtomInfo($data);
        if(!$Ret){
            $this->ajaxReturn(array('i' => '刪除失敗', 't' => 0));
        }
        $this->ajaxReturn(array('i' => '刪除成功', 't' => 1));
    }

    public function center(){
        $Ret = ButtomModel::getButtomInfo(ButtomModel::BUTTOM_TYPE_CENTER);
        $this->assign('list',$Ret);
        $this->display();
    }
    public function center_add(){
        $link_id = I('get.id','','trim');
        $data = array();
        if($link_id){
            $condition['id'] = $link_id;
            $data = ButtomModel::getButtomInfoByWhere($condition);
        }
        $this->assign('link',$data[0]);
        $this->display();
    }
    public function center_add_action(){
        $link_id = I('post.id','','trim');
        $link_name = I('post.link_name','','trim');
        $link_content = I('post.content','','trim');
        if(!$link_id) {
            $data = array(
                'link_name' => $link_name,
                'content' => self::strFalter($link_content),
                'type' => ButtomModel::BUTTOM_TYPE_CENTER,
                'status' => ButtomModel::BUTTOM_STATUS_YES,
                'create_time' => Date('Y-m-d H:i:s', time()),
            );
            ButtomModel::addButtomInfo($data);
        }else{
            $data  = array(
                'id'=>$link_id,
                'link_name'=>$link_name,
                'content'=>self::strFalter($link_content),
                'update_time'=>Date('Y-m-d H:i:s', time()),
            );
            ButtomModel::updButtomInfo($data);
        }
        $this->assign('link',$data);
        $this->display('center_add');
    }
    public function center_del(){
        $link_id = I('get.id','','trim');
        if(!$link_id){
            $this->ajaxReturn(array('i' => '参数有误', 't' => 0));
        }
        $data  = array(
            'id'=>$link_id,
            'status'=>ButtomModel::BUTTOM_STATUS_NO,
            'update_time'=>Date('Y-m-d',time()),
        );
        $Ret =  ButtomModel::updButtomInfo($data);
        if(!$Ret){
            $this->ajaxReturn(array('i' => '刪除失敗', 't' => 0));
        }
        $this->ajaxReturn(array('i' => '刪除成功', 't' => 1));
    }

    public function right(){
        $Ret = ButtomModel::getButtomInfo(ButtomModel::BUTTOM_TYPE_RIGHT);
        $this->assign('list',$Ret);
        $this->display();
    }
    public function right_add(){
        $link_id = I('get.id','','trim');
        $data = array();
        if($link_id){
            $condition['id'] = $link_id;
            $data = ButtomModel::getButtomInfoByWhere($condition);
        }
        $this->assign('link',$data[0]);
        $this->display();
    }
    public function right_add_action(){
        $link_id = I('post.id','','trim');
        $link_name = I('post.link_name','','trim');
        $link_content = I('post.content','','trim');
        if(!$link_id) {
            $data = array(
                'link_name' => $link_name,
                'content' => self::strFalter($link_content),
                'type' => ButtomModel::BUTTOM_TYPE_RIGHT,
                'status' => ButtomModel::BUTTOM_STATUS_YES,
                'create_time' => Date('Y-m-d H:i:s', time()),
            );
            ButtomModel::addButtomInfo($data);
        }else{
            $data  = array(
                'id'=>$link_id,
                'link_name'=>$link_name,
                'content'=>self::strFalter($link_content),
                'update_time'=>Date('Y-m-d H:i:s', time()),
            );
            ButtomModel::updButtomInfo($data);
        }
        $this->assign('link',$data);
        $this->display('center_add');
    }
    public function right_del(){
        $link_id = I('get.id','','trim');
        if(!$link_id){
            $this->ajaxReturn(array('i' => '参数有误', 't' => 0));
        }
        $data  = array(
            'id'=>$link_id,
            'status'=>ButtomModel::BUTTOM_STATUS_NO,
            'update_time'=>Date('Y-m-d',time()),
        );
        $Ret =  ButtomModel::updButtomInfo($data);
        if(!$Ret){
            $this->ajaxReturn(array('i' => '刪除失敗', 't' => 0));
        }
        $this->ajaxReturn(array('i' => '刪除成功', 't' => 1));
    }

    public function buttom_title(){
        $Ret = ButtomModel::getAllButtomTitle();
        $type_name = ButtomModel::getTypeConfig();
        $this->assign('list',$Ret);
        $this->assign('type_name',$type_name);
        $this->display('buttom');
    }
    public function buttom_title_add(){
        $link_id = I('get.id','','trim');
        $data = array();
        if($link_id){
            $condition['id'] = $link_id;
            $data = ButtomModel::getButtomTitleByWhere($condition);
        }
        $titleType = ButtomModel:: getTypeConfig();
        $this->assign('titleType',$titleType);
        $this->assign('title',$data[0]);
        $this->display("buttom_add");
    }
    public function buttom_title_add_action(){
        $id = I('post.id','','trim');
        $title = I('post.title','','trim');
        $title_type = I('post.title_type','','trim');
        if(!$id) {
            $data = array(
                'title' => $title,
                'type' => $title_type,
                'status' => ButtomModel::BUTTOM_STATUS_YES,
                'create_time' => Date('Y-m-d H:i:s', time()),
            );
            ButtomModel::addButtomTitle($data);
        }else{
            $data  = array(
                'id'=>$id,
                'title'=>$title,
                'type'=>$title_type,
                'update_time'=>Date('Y-m-d H:i:s', time()),
            );
            ButtomModel::updButtomTitle($data);
        }
        $titleType = ButtomModel:: getTypeConfig();
        $this->assign('titleType',$titleType);
        $this->assign('title',$data);
        $this->display("buttom_add");
    }
    public function buttom_title_del(){
        $id = I('get.id','','trim');
        if(!$id){
            $this->ajaxReturn(array('i' => '参数有误', 't' => 0));
        }
        $data  = array(
            'id'=>$id,
            'status'=>ButtomModel::BUTTOM_STATUS_NO,
            'update_time'=>Date('Y-m-d',time()),
        );
        $Ret =  ButtomModel::updButtomTitle($data);
        if(!$Ret){
            $this->ajaxReturn(array('i' => '刪除失敗', 't' => 0));
        }
        $this->ajaxReturn(array('i' => '刪除成功', 't' => 1));
    }
}