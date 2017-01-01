<?php
/**
 * Filename: BannerController.class.php
 * Comment: 品項管理
 * User: lxd
 * Time: 16/1/25 22:06
 */
namespace Admin\Controller;

class BannerController extends CommonController
{
    private $_banner_model = null;

    public function __construct()
    {
        parent::__construct();
        $this->_banner_model = M('banner');
    }

    /**
     * 列表
     */
    public function getList()
    {
        $count = $this->_banner_model->where(array('pid' => 0))->count();
        $this->_pageIndex($count);
        $page_limit = $this->_page($count);
        $list = $this->_banner_model->where(array('pid' => 0))->order('id desc')->page($page_limit)->select();
        if ($list === false)
            $this->error('服務器異常');

        $this->assign('list', $list);
        $this->display('list');
    }

    /**
     * 新增
     */
    public function add()
    {
        if (IS_POST) {

            $name = I('post.name', '', 'trim');
            if (empty($name))
                $this->error('非法參數');

            $missmoney = I('post.missmoney', 0, 'intval');

            $missmoney_arr = $_POST['dis'];

            //折扣處理
            $tmp_arr = array();
            foreach ($missmoney_arr['count'] as $k => $v) {
                if (!empty($v)) {
                    $tmp_arr[$v] = $missmoney_arr['dis'][$k];
                }
            }

            foreach ($tmp_arr as $k => $v) {
                if (empty($v))
                    unset($tmp_arr[$k]);
            }

            $is_exists = $this->_banner_model->where(array('name' => $name))->find();
            if ($is_exists === false)
                $this->error('服務器異常');
            else if ($is_exists)
                $this->error('已經存在該品項');

            $insert_res = $this->_banner_model->add(array('name' => $name, 'add_time' => time()));
            if (!$insert_res)
                $this->error('服務器異常');

            //折扣入庫
            $discount_model = M('discount');
            foreach ($tmp_arr as $k => $v) {
                $insert_res = $discount_model->add(array(
                    'bid' => $insert_res,
                    'count' => $k,
                    'discount' => $v,
                    'missmoney' => $missmoney
                ));
                if (!$insert_res)
                    $this->error('服務器新增折扣異常');
            }

            $this->redirect('getList');
        }

        $this->display();
    }

    /**
     * 編輯
     */
    public function mof()
    {
        if (IS_POST) {
            $id = I('post.id', 0, 'intval');
            if ($id <= 0)
                $this->error('非法操作');

            $name = I('post.name', '', 'trim');
            if (empty($name))
                $this->error('非法參數');

            $missmoney = I('post.missmoney', 0, 'intval');

            $missmoney_arr = $_POST['dis'];

            //折扣處理
            $tmp_arr = array();
            foreach ($missmoney_arr['count'] as $k => $v) {
                if (!empty($v)) {
                    $tmp_arr[$v] = $missmoney_arr['dis'][$k];
                }
            }

            foreach ($tmp_arr as $k => $v) {
                if (empty($v))
                    unset($tmp_arr[$k]);
            }

            $is_exists = $this->_banner_model->where(array('name' => $name, 'id' => array('neq', $id)))->find();
            if ($is_exists === false)
                $this->error('服務器異常');
            else if ($is_exists)
                $this->error('已經存在該品項');

            //清理之前的，
            $up_res = $this->_banner_model->where(array('id' => $id))->save(array(
                'name' => $name
            ));
            if (false === $up_res)
                $this->error('服務器更新數據異常');

            //折扣入庫
            $discount_model = M('discount');
            $del_res = $discount_model->where(array('bid' => $id))->delete();
            if (false === $del_res)
                $this->error('服務器更新數據異常1');

            foreach ($tmp_arr as $k => $v) {
                $insert_res = $discount_model->add(array(
                    'bid' => $id,
                    'count' => $k,
                    'discount' => $v,
                    'missmoney' => $missmoney
                ));
                if (!$insert_res)
                    $this->error('服務器更新折扣異常2');
            }

            $this->redirect('getList');
        }
        $id = I('get.id');
        if ($id <= 0)
            $this->error('非法操作');

        //查詢數據
        $info = $this->_banner_model->where(array('id' => $id))->find();
        if (!$info)
            $this->error('服務器查詢異常1');

        //折扣
        $discount_model = M('discount');
        $discount_list = $discount_model->where(array('bid' => $id))->select();
        if ($info === false)
            $this->error('服務器查詢異常2');

        if ($info) {
            $info['missmoney'] = $discount_list[0]['missmoney'];
            $info['dis'] = $discount_list;
        } else {
            $info['missmoney'] = 0;
            $info['dis'] = array();
        }

        $this->assign('info', $info);
        $this->display('add');
    }

    /**
     * 刪除
     */
    public function del()
    {
        if (IS_AJAX) {
            $id = I('get.id', 0, 'intval');
            if ($id <= 0)
                $this->ajaxReturn(array('status' => 0, 'data' => '非法傳值'), 'json');

            //查詢是否佔用
            $is_used = $this->_banner_model->where(array('pid' => $id))->find();
            if (false === $is_used)
                $this->ajaxReturn(array('status' => 0, 'data' => '服務器查詢異常'), 'json');
            else if ($is_used)
                $this->ajaxReturn(array('status' => 0, 'data' => '該品項下有商品，請先刪除商品'), 'json');

            $discount_model = M('discount');
            $del_res = $discount_model->where(array('bid' => $id))->delete();
            if (false === $del_res)
                $this->ajaxReturn(array('status' => 0, 'data' => '服務器清理折扣異常'), 'json');

            $del_res = $this->_banner_model->where(array('id' => $id))->delete();
            if (false === $del_res)
                $this->ajaxReturn(array('status' => 0, 'data' => '服務器異常'), 'json');

            $this->ajaxReturn(array('status' => 1, 'data' => '刪除成功'), 'json');

        }
    }
}