<?php
/**Remark
 * Created by PhpStorm.
 * User: congquan
 * Date: 2016/3/1
 * Time: 20:05
 */
namespace  Admin\Controller;
use Home\Model\AttentionModel;

class AttentionController extends CommonController{
    public function index(){
        $list = AttentionModel::getAllInfo();
        $this->assign('list',$list);
        $this->display();
    }
    public function addAttention(){
        $attentionId = I('id');
        $attentionName = I('name');
        $attentionContent = I('content');
        //$attentionContent = str_replace('&quot;','',str_replace('\\','',$_POST['content']));
        $attentionContent = self::strFalter($attentionContent);
        $time = Date('Y-m-d H:i:s',time());
        $data = array();
        if($attentionName){
            $data['name'] = $attentionName;
        }
        if($attentionContent){
            $data['content'] = $attentionContent;
        }
        if(!$attentionId){
            //添加新內容
            $data['create_time'] = $time;
            $data['status'] = AttentionModel::NORMAL_STATUS;
            AttentionModel::addInfo($data);
        }else{
            //編輯內容
            $data['id'] = $attentionId;
            $data['modify_time'] = $time;
            AttentionModel::saveInfo($data);
        }

        if($attentionId){
            $data = AttentionModel::getOneInfoById($attentionId);
        }
        $this->assign('info',$data);
        $this->display('add');
    }
    public function removeAttention(){
        if(IS_AJAX){
            $attentionId = I('get.id');
            if(!$attentionId){
                $this->ajaxReturn(array('t' => 0,'i' => '刪除信息失敗'));
            }

            $data = array('id'=>$attentionId,'status'=>AttentionModel::DELETE_STATUS);
            $Ret = AttentionModel::saveInfo($data);
            if(!$Ret){
                $this->ajaxReturn(array('t' => 0,'i' => '刪除信息失敗'));
            }
            $this->ajaxReturn(array('t' => 1,'i' => '刪除信息成功'));
        }
    }

    public static function getAttentionInfo(){
        return AttentionModel::getOneInfo();
    }
}