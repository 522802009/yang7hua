<?php

use \Common\Func as Func;

class SearchController extends Controller{

	private static $reason = array(1=>'贷款审批', 2=>'担保资格审批');
	private static $card_type = array(1=>'身份证');

	public function indexAction()
	{
		$this->view->setVar('reason', static::$reason);
		$this->view->setVar('userinfo', array('username'=>$this->session->get('username')));
		$this->display();
	}
	
	public function queryAction()
	{
		$reason = $this->request->get('reason', 'int');
		$card_number = $this->request->get('cardnumber');
		$realname = $this->request->get('realname');

		$params = $this->getParams();

		if(empty($reason) || empty($card_number) || empty($realname))
			$this->ajaxReturn('参数错误', false);
		if(!preg_match("/^[\x{4E00}-\x{9Fa5}]+$/u", $realname))
			$this->ajaxReturn('姓名格式不正确', false);
		if(!preg_match('/^[\dxX]{18}$/', $card_number))
			$this->ajaxReturn('身份证号格式不正确', false);

		$User = new User();
		$userinfo = $User::findFirst(array(
						'idcard = :idcard: and realname = :realname:',
						'bind' => array('idcard'=>$card_number, 'realname'=>$realname)
					));
		if(!$userinfo)
			$this->ajaxReturn('没有数据', false);

		$card_type = 1;
		$params['card_type'] = $card_type;
		$params['card_number'] = $card_number;
		$params['reason'] = $reason;
		$params['member_id'] = $this->getMemberId();
		$params['addtime'] = time();

		$Record = new Record();

		$record['list'] = $Record->select(array(
							$record_select_where,
							'limit'	=> Func\limit($params['p'], $params['limit'])
					));
		$count = $Record->count($record_select_where);
		$record['page'] = $this->page($count, $params['limit']);

		$Record->insert($params, false);

		$this->ajaxReturn($record);
	}

}
