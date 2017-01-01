<?php
    /**
     * Filename: ColorController.class.php
     * Comment:
     * User: lxd
     * Time: 16/1/26 09:32
     */
    namespace Admin\Controller;

    class SizeController extends CommonController {
        private $_model          = null;

        public function __construct() {
            parent::__construct();
            $this->_model        = M('size');
        }

        /**
         * 列表
         */
        public function getList() {
            $count          = $this->_model->count();
            $this->_pageIndex($count);
            $page_limit     = $this->_page($count);
            $list           = $this->_model->order('id desc')->page($page_limit)->select();
            if($list === false)
                $this->error('服務器異常');

            $this->assign('list',$list);
            $this->display('list');
        }

        /**
         * 新增
         */
        public function add() {
            if(IS_POST){
                $name           = I('post.name','','trim');
                if(empty($name))
                    $this->error('服務器異常');

                $is_exists      = $this->_model->where(array('name' => $name))->find();
                if($is_exists === false)
                    $this->error('服務器異常');
                else if($is_exists)
                    $this->error('已經存在該尺寸');

                $insert_res     = $this->_model->add(array('name' => $name));
                if(!$insert_res)
                    $this->error('服務器異常');

                $this->redirect('getList');
            }

            $this->display();
        }

        /**
         * 刪除
         */
        public function del() {
            if(IS_AJAX) {
                $id             = I('get.id',0,'intval');
                if($id <= 0)
                    $this->ajaxReturn(array('status' => 0,'data' => '非法傳值'),'json');

                //查詢是否佔用
                //$is_used        = $this->_model->where(array('pid' => $id))->find();
                //if(false === $is_used)
                //    $this->ajaxReturn(array('status' => 0,'data' => '服務器查詢異常'),'json');
                //else if($is_used)
                //    $this->ajaxReturn(array('status' => 0,'data' => '該品項下有商品，請先刪除商品'),'json');

                $del_res        = $this->_model->where(array('id' => $id))->delete();
                if(false === $del_res)
                    $this->ajaxReturn(array('status' => 0,'data' => '服務器異常'),'json');

                $this->ajaxReturn(array('status' => 1,'data' => '刪除成功'),'json');

            }
        }
    }