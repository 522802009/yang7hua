<?php


class UserModel extends Model{

	protected $_validate = array(
		array('memberid','require','公司ID不能为空！',1),
		array('idcard','require','身份证号不能为空！',1),
		array('idcard','/^[\dxX]{18}$/','身份证号格式不正确',0,'regex '),
		array('realname','require','真实姓名不能为空！',1)
	);

}