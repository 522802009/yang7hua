<?php


class AdminModel extends Model{


	protected $_validate = array(
		array('username','require','登录账号不能为空',1),
		array('password','require','登录密码不能为空',1),
	);


}
