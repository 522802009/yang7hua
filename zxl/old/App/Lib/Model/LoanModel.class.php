<?php


class LoanModel extends RelationModel{
	
	protected $_validate = array(
		array('time','require','借款时间不能为空！',1),
		array('idcard','require','身份证号不能为空！',1),
		array('idcard','/^[\dxX]{18}$/','身份证号格式不正确',0,'regex '),
		array('realname','require','真实姓名不能为空',1),
		array('money','require','借款金额不能为空！',1),
		array('status','require','借款状态不能为空！',1)
	);
	
	
	protected $_link = array(
		'Member' => array(
			'mapping_type'	=>	BELONGS_TO,
			'class_name'	=>	'Member',
			'foreign_key'	=>	'memberid',
			'mapping_fields'=>	array('companyname')
		),
		'User'	=>	array(
			'mapping_type' 	=> BELONGS_TO,
			'class_name'   	=> 'User',
			'foreign_key'	=>	'uid',
			'mapping_fields'=>	array('idcard', 'realname')
		)
	);
	
}
