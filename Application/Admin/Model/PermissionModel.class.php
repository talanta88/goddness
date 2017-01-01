<?php
	/*
		权限模型类
	*/
	namespace Admin\Model;
	use Think\Model;

	class PermissionModel extends Model {
		//入库数据自动验证
		protected $_validate 		= array(
												array('name',			'1,30',			'权限名称1-30位内',	self::MUST_VALIDATE,	'length',	self::MODEL_BOTH),
												array('name',			'',				'权限名称重复',		self::MUST_VALIDATE,	'unique',	self::MODEL_INSERT),
												array('contro_name',	'require',		'控制器名称不能为空',	self::MUST_VALIDATE,	'regex',	self::MODEL_BOTH),
												array('parent_id',		'number',		'所属类别必须是数字',	self::MUST_VALIDATE,	'regex', 	self::MODEL_BOTH),
												array('action_name',	'checkAction',	'方法名称不能为空',	self::MUST_VALIDATE,	'callback',	self::MODEL_BOTH),
												array('is_show',		array(0,1), 	'非法值',			self::MUST_VALIDATE,	'in',		self::MODEL_BOTH),
											);

		//自动完成
		protected $_auto 			= array(
												array('contro_name',	'trunAa',	self::MODEL_BOTH,	'callback'),
											);

		//检查
		public function checkAction() {
			$parent_id 			= I('post.parent_id',0);
			$action_name 		= I('post.action_name','','trim');
			if($parent_id)
				if(empty($action_name))
					return false;
			return true;
		}

		//转换首字母大写
		public function trunAa() {
			$contro_name 		= I('post.contro_name','');
			$contro_name 		= strtolower($contro_name);
			return ucfirst($contro_name);
		}
	}