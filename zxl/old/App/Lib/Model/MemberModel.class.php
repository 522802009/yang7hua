<?php


class MemberModel extends Model{
	
	protected $_validate = array(
		array('address','require','地址不能为空',1),
		array('contact1','require','公司联系人不能为空',1),
		array('contact1_phone','require','联系人电话不能为空',1),
		array('contact1_phone','/^[\d-]{11,12}$/','电话格式不正确',0,'regex'),
		array('contact2_phone','/^[\d-]{11,12}$/','电话格式不正确',0,'regex')
	);

	
}
