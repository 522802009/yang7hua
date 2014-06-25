<?php


class LoanAction extends BaseAction{

	private static $_LOAN;

	const STATUS_FINISHED 	= 1; //已结清
	const STATUS_UNFINISHED = 2; //还款中


	public function __construct(){
		if(!self::$_LOAN instanceof Model){
			self::$_LOAN = M('Loan');
		}

		parent::__construct();
	}

	public function count(array $where=array())
	{
		$count = self::$_LOAN->where($where)->count();
		return $count;
	}

	public function getFinishedCount(array $where=array())
	{
		$where['status'] = self::STATUS_FINISHED;
		return $this->count($where);
	}

	public function getUnfinishedCount(array $where=array())
	{
		$where['status'] = self::STATUS_UNFINISHED;
		return $this->count($where);
	}

	public function getFinished(array $where=array())
	{
		$where['status'] = self::STATUS_FINISHED;
		return $this->_select($where);
	}

	public function getUnfinished(array $where=array())
	{
		$where['status'] = self::STATUS_UNFINISHED;
		return $this->_select($where);
	}

	private function _select(array $where=array())
	{
		$params = $this->getParams();

		$res = self::$_LOAN->where($where)->page($params['p'], $params['limit'])->select();

		$count = $this->count($where);
		$page = $this->page($count, $params['limit']);

		$data = array(
				'list' => $res,
				'page' => $page
		);

		return $data;
	}

	public function data()
	{
		$data = $this->_select();
		if($this->isAjax())
			$this->ajaxReturn($data);
		return $data;
	}

}
