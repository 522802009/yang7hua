<?php

class Member extends Model
{

	protected $selectFields = array(array('password'), true);
	
	protected $_validators = array(
				array('username', 'require', '用户名不能为空'),	
				array('password', 'require', '密码不能为空'),
				array('companyname', 'require', '公司名称不能为空')
			);

}
