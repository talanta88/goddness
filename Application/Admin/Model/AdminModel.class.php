<?php
	/*
		管理员模型
	*/
	namespace Admin\Model;
	use Think\Model;

	class AdminModel extends Model{
		protected $_validate 		= array(
			array('name',	'2,30',	'用户名必须在2-30位之间',	self::MUST_VALIDATE,	'length',	self::MODEL_BOTH),
			// array('name',	'',		'该名称已经被占用',		self::EXISTS_VALIDATE,	'unique',	self::MODEL_INSERT), //登录的时候一直回验证到？？
			array('pass',	'6,100','密码最少6位',			self::MUST_VALIDATE,	'length',	self::MODEL_BOTH),
		);



		/*
			登录
		*/
		public function login($where) {
			$where['pass'] 	= md5(C('PASS_RAND') . $where['pass']);

			$res 			= $this->field('id,is_super,role_ids,last_ip,last_time')->where($where)->find();
			if(!$res)
				return false;
			return $res;
		}

		/*
			更新最后登录时间，ip
		*/
		public function reLastInfo($id,$data) {
			$up_res 		= $this->where(array('id' => $id))->save($data);
			if(!$up_res)
				return false;
			return true;
		}

	}
