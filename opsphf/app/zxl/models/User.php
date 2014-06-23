<?php

use Phf\Mvc\Model\Validator\Regex as RegexValidator;

class User extends Model
{

	protected $_validators = array(
								array('idcard', 'regex', '身份证号错误', '/^[\dxX]{18}$/'),
								array('idcard', 'unique', '身份证号已存在')
							);

}
