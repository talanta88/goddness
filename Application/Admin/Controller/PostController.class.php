<?php
    /**
     * Filename: GoodsController.class.php
     * Comment:
     * User: lxd
     * Time: 16/1/25 16:02
     */
    namespace Admin\Controller;

    class PostController extends CommonController {
        private $_model             = null;

        public function __construct() {
            parent::__construct();
            $this->_model           = M('post');
        }

        /**
         * 獲取列表
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
            $posttype_model         = M('posttype');

            if(IS_POST) {
                $type            = I('post.type',0,'intval');
                if($type <= 0)
                    $this->error('請選擇郵寄類型');

                $name           = I('post.name','','trim');
                if(empty($name))
                    $this->error('請填寫名稱');

                $price          = I('post.price','','trim');
                if(empty($price) || $price == '0.00')
                    $this->error('請填寫價格');

                $pname          = $posttype_model->where(array('id' => $type))->find();

                $is_exists      = $this->_model->where(array('name' => $name,'pid' => $type))->find();
                if($is_exists)
                    $this->error('已經存在該名稱');


                $insert_res     = $this->_model->add(array(
                    'name'      => $name,
                    'price'     => $price,
                    'pid'       => $type,
                    'pname'     => $pname['name']
                ));

                $this->redirect('getList');
            }
            //讀取信息
            $type                   = $posttype_model->select();

            $this->assign('type',$type);
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

                $del_res        = $this->_model->where(array('id' => $id))->delete();
                if(!$del_res)
                    $this->error('服務器異常' . $this->_model->getDbError());

                $this->ajaxReturn(array('status' => 1,'data' => '刪除成功'),'json');

            }
        }
    }