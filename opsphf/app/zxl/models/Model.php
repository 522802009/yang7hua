<?php

class Model extends \Phf\Mvc\Model{
	protected $db;
	protected $selectFields = array();

	protected function initialize()
	{
		global $di;
		$this->db = $di->get('db');
	}

	protected function format($data)
	{
		$result = array();
		foreach($data as $row){
			$row = (array) $row;
			$item = array();
			foreach($this->selectFields as $field){
				$item[$field] = $row[$field];
			}
			$result [] = $item;
		}
		return $result ;
	}

}
