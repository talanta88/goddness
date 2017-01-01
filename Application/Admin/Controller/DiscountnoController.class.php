<?php
/**
 * Filename: DiscountnoController.class.php
 * Comment:
 * User: lxd
 * Time: 16/1/26 14:06
 */
namespace Admin\Controller;

class DiscountnoController extends CommonController
{

    private $_model = null;
    /**
     * 限期券
     */
    const TIME_TYPE = 1;
    /**
     * 長期券
     */
    const LONG_TIME_TYPE = 2;

    public function __construct()
    {
        parent::__construct();
        $this->_model = M('discountno');
    }

    /**
     * @var array
     * 優惠券類型配置
     */
    private static $discountNoTypeConfig = array(
        self::TIME_TYPE => '一次券',
        self::LONG_TIME_TYPE => '多次券'
    );

    public static function getDiscountTypeConfig()
    {
        return self::$discountNoTypeConfig;
    }

    /**
     * 列表
     */
    public function getList()
    {
        $count = $this->_model->count();
        $this->_pageIndex($count);
        $page_limit = $this->_page($count);
        $list = $this->_model->order('id desc')->page($page_limit)->select();
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
                $this->error('請填寫優惠券號碼');

            $type = I('post.type', '', 'trim');
            if (empty($type))
                $this->error('请選擇優惠券類型');

            $start = I('post.start', '', 'trim');
            if (empty($start))
                $this->error('請選擇開始時間');

            $end = I('post.end', '', 'trim');
            if (empty($end))
                $this->error('請選擇結束時間');

            $price = I('post.price', '', 'trim');
            if (empty($price) || $price == '0.00')
                $this->error('請輸入減免金額');

            $start_time = strtotime($start);
            $end_time = strtotime($end);

            $is_exists = $this->_model->where(array('no' => $name))->find();
            if ($is_exists === false)
                $this->error('服務器異常');
            else if ($is_exists)
                $this->error('已經存在該優惠券');

            $insert_res = $this->_model->add(array(
                'no' => $name,
                'start' => $start_time,
                'end' => $end_time,
                'add_time' => time(),
                'type' => $type,
                'price' => $price
            ));
            if (!$insert_res)
                $this->error('服務器異常');

            $this->redirect('getList');
        }
        $this->assign('type',self::$discountNoTypeConfig);
        $this->display();
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

            $del_res = $this->_model->where(array('id' => $id))->delete();
            if (false === $del_res)
                $this->ajaxReturn(array('status' => 0, 'data' => '服務器異常'), 'json');

            $this->ajaxReturn(array('status' => 1, 'data' => '刪除成功'), 'json');

        }
    }

}