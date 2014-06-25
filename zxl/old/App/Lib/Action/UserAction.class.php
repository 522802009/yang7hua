<?php

class UserAction extends BaseAction{

	private static $_USER;

	public function __construct(){
		R('Member/check');
		if(!self::$_USER instanceof Model)
			self::$_USER = D('User');
	}

	public function find($where){

		$where = $this->where($where);

		$result = self::$_USER->where($where)->find();
		
		return $result;
	}

	private function where($where){
		if(is_array($where)){
			$where = $where;
		}else if(is_int($type)){
			$where['uid'] = $type;
		}
		return isset($where) ? $where : '';
	}

	public function select($where, $field){
		$where = $this->where($where);
		return $this->_select($where, $field);
	}

	private function _select($where, $field){

		$p = isset($_REQUEST['p']) ? $this->_request('p') : 1;
		$limit = isset($_REQUEST['limit']) ? $this->_request('limit') : 10;

		import("ORG.Util.Page");

		$return['list'] = self::$_USER->where($where)->page($p, $limit)->field($field)->select();
		$return['count'] = $count = self::$_USER->where($where)->count();		
		$Page = new Page($count, $limit);
		$return['page'] = $Page->getPage();

		return $return;

	}

	public function add($data){
		$result = self::$_USER->create($data);
		if(!$result){		
			return false;
		}		

		$data['addtime'] = $data['mtime'] = time();
		
		$result = self::$_USER->add($data);
		if(!$result){
			return false;
		}
		return self::$_USER->getLastInsId();
	}

	public function update($uid, $data){
		return self::$_USER->where("uid=$uid")->save($data);
	}

	public function save(){
		$uid = intval($this->_post('uid'));
		$data['idcard'] = $this->_post('idcard');

		//校验是否存在
		$row = $this->find("memberid={$_SESSION['memberid']} and idcard='{$data['idcard']}' and uid!=$uid");
		
		if($row){
			$this->ajaxReturn(array(
					'return' => 0,
					'errmsg' => '该身份证已存在！'
				));
		}

		$data['realname'] = $this->_post('realname');
		$data['memberid'] = $_SESSION['memberid'];

		$result = self::$_USER->create($data);
		if(!$result){
			$this->ajaxReturn(array(
					'return' => 0,
					'errmsg' => self::$_USER->getError()
				));
		}		

		$this->update($uid, $data);
		
		$this->ajaxReturn(array(
				'return' => 1,
				'msg' => '操作成功！'
			));
		
	}

}