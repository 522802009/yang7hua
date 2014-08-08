<?php

class Record extends Model{

	protected $tableName = 'record';

	public function insert(array $params, $ajaxReturn = true)
	{
		$params['tablename'] = $this->tableName;	
		$params['fields'] = array('member_id', 'card_number', 'reason', 'addtime');
		parent::setQueryOptions($params)->doInsert($ajaxReturn);
	}

}
