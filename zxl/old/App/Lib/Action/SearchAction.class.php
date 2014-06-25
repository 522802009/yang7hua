<?php

class SearchAction extends BaseAction{

	public function __construct(){
		parent::__construct();
	}

	public function index(){

		if(!$this->isAjax()){
			$this->assign('reason', RecordAction::getReason());
			$this->display();
			return;
		}
		$params = $this->getParams();

		if(!in_array($params['reason'], self::$reason)){
			$this->ajaxReturn('查询原因错误', false);
		}else if(!preg_match('/^[\dxX]{18}$/', $params['idcard'])){
			$this->ajaxReturn('身份证号不正确！', false);
		}else if(!preg_match("/^[\x{4E00}-\x{9Fa5}]+$/u", $params['realname'])){
			$this->ajaxReturn('姓名不正确！', false);
		}else{
			$where['idcard'] = $params['idcard'];
			$where['realname'] = $params['realname'];
		}

		R('/Record/add', array(
			array(
				'mid' => $_SESSION['memberid'],
				'reason' => $params['reason'],
				'realname' => $where['realname'],
				'card_type' > $where['card_type'],
				'card_number' => $where['card_number']
			)
		));

		$M_USER = M('User');
		$res = $M_USER->where($where)->find();

		if(count($res) < 1){
			$this->ajaxReturn('该用户不存在！', false);
		}

		$uid = $res['uid'];

		$this->ajaxReturn($data);
	}


}
