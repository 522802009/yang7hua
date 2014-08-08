<?php

use \Phf\Mvc\Model\Validator\PresenceOf as PresenceOfValidator;
use \Phf\Mvc\Model\Validator\Email as EmailValidator;
use \Phf\Mvc\Model\Validator\ExclusionIn as ExclusionInValidator;
use \Phf\Mvc\Model\Validator\InclusionIn as InclusionInValidator; 
use \Phf\Mvc\Model\Validator\Numericality as NumericalityValidator; 
use \Phf\Mvc\Model\Validator\Regex as RegexValidator; 
use \Phf\Mvc\Model\Validator\Uniqueness as UniquenessValidator; 
use \Phf\Mvc\Model\Validator\StringLength as StringLengthValidator; 

use \Phf\Mvc\Model\Resultset; 
use \Phf\Mvc\Model\MetaData\Memory as MetaDataMemory;

use \Common\Func as Func;

class Model extends \Phf\Mvc\Model
{
	
	protected static $db;

	private $_metadataMemory;

	private $_allowValidators = array('require', 'email', 'notin', 'in', 'number', 'regex', 'unique', 'stringlength');

	protected $_validators;

	private $_queryOptions;

	protected function initialize()
	{
		global $di;
		static::$db = $di->get('db');
		//print_r(Func\getMethodsOfClass($this->db));
		$this->_metadataMemory = new MetaDataMemory;
	}

	public static function getDb()
	{
		return static::$db;
	}
	
	protected function beforeValidation()
	{
		if(!isset($this->_validators))
			return;
		$validators = $this->_validators;
		if(is_string($validators[0])){
			$this->setValidator($validators);
		}else{
			foreach($validators as $validator){
				$this->setValidator($validator);
			}
		}
	}

	protected function validation()
	{
		if($this->validationHasFailed() == true)
			return false;
	}

	protected function setValidator($validator)
	{
		list($field, $valiType, $message, $rule) = $validator;

		$valiType = strtolower($valiType);
		if(!in_array($valiType, $this->_allowValidators))
			return false;

		switch($valiType){
			case 'require':
				$this->validate(new PresenceOfValidator(array(
									'field'	=>	$field,
									'message'	=>	$message
								)));
				break;
			case 'email':
				$this->validate(new EmailValidator(array(
									'field'	=>	$field,
									'message'	=>	$message
								)));
				break;
			case 'notin':
				$this->validate(new ExclusionInValidator(array(
									'field'	=>	$field,
									'domain'	=> (array) $rule,
									'message'	=>	$message
								)));
				break;
			case 'in':
				$this->validate(new InclusionInValidator(array(
									'field'	=>	$field,
									'domain'	=> (array) $rule,
									'message'	=>	$message
								)));
				break;
			case 'number':
				$this->validate(new NumericalityValidator(array(
									'field'	=>	$field,
									'message'	=>	$message
								)));
				break;
			case 'regex':
				$this->validate(new RegexValidator(array(
									'field'	=>	$field,
									'pattern'	=>	$rule,
									'message'	=>	$message
								)));
				break;
			case 'unique':
				$this->validate(new UniquenessValidator(array(
									'field'	=>	$field,
									'message'	=>	$message
								)));
				break;
			case 'strlen':
				$rule = (array) $rule;
				$this->validate(new StringLengthValidator(array(
									'field'	=>	$field,
									'min'	=>	$rule[0],
									'max'	=>	$rule[1],
									'message'	=>	$message
								)));
				break;
		}
	}

	public function first(array $params = array())
	{
		$data = $this->findFirst($params);
		return $this->filter($data, $params['fields']);
	}

	public function select(array $params = array())
	{
		$data = $this->find($params);
		return $this->filter($data, $params['fields']);
	}

	/**
	  format fields
	*/
	private function formatFields($allowFields = '*')
	{
		$allFields = $this->getTableFields();
		$filter = false;
		if(empty($allowFields))
			$allowFields = $this->selectFields ? $this->selectFields : $allFields;
		if($allowFields == '*'){
			$fields = $allFields;
		}else if(is_string($allowFields)){
			$fields = $allowFields;
		}else if(is_array($allowFields)){
			if(is_array($allowFields[0])){
				$fields = $allowFields[0];
				$filter = isset($allowFields[1]) ? $allowFields[1] : false;
			}else{
				$fields = $allowFields;
			}
		}

		if(is_string($fields) && strpos($fields, ','))
			$fields = explode(',', $fields);
		else 
			$fields = (array) $fields;
		if($filter){
			$fields = array_diff($allFields, $fields);
		}
		return $fields;
	}

	private function filter($data, $allowFields = '*')
	{
		$fields = $this->formatFields($allowFields);	

		$return = array();

		$filter = function($row) use ($fields){
			foreach($row as $k=>$v){
				if(!in_array($k, $fields)){
					unset($row[$k]);
				}
			}
			return $row;
		};

		$data = $data->toArray();
		if(is_array($data[0])){
			foreach($data as $row)
				$return[] = $filter($row);
		}else{
			$return = $filter($data);
		}

		return $return;
	}

	protected function getTableFields()
	{
		return $this->_metadataMemory->getAttributes($this);
	}

	public function setQueryOptions(array $params)
	{
		$queryOptions = array(
				'fields'=>array(),
				'values'=>array()
		);
		$allowFields = $this->formatFields(isset($params['fields']) ? $params['fields'] : '*');

		foreach($params as $key=>$value){
			$key = strtolower($key);
			if(in_array($key, $allowFields)){
				$queryOptions['fields'][] = $key;
				$queryOptions['values'][] = $value;
			}
			if($key == 'tablename' && $this->getDb()->tableExists($value))
				$queryOptions['tableName'] = $value;
			if($key == 'where')
				$queryOptions['where'] = $value;
		}
		$this->_queryOptions = $queryOptions;
		$this->_checkQueryOptions();
		return $this;
	}

	private function _checkQueryOptions()
	{
		if(!self::$db->tableExists($this->_queryOptions['tableName'])){
			$this->ajaxReturn('参数错误', false);	
		}
		if(empty($this->_queryOptions['fields']) || empty($this->_queryOptions['values'])
			|| count($this->_queryOptions['fields']) != count($this->_queryOptions['values'])
		){
			$this->ajaxReturn('参数错误', false);	
		}
	}

	public function doUpdate($ajaxReturn = true)
	{
		$this->doResult = $this->getDb()->update(
					$this->_queryOptions['tableName'],
					$this->_queryOptions['fields'],
					$this->_queryOptions['values'],
					$this->_queryOptions['where']
				);
		return $this->returnDoResult($ajaxReturn);
	}

	public function doInsert($ajaxReturn = true)
	{
		$this->doResult = $this->getDb()->insert(
					$this->_queryOptions['tableName'],
					$this->_queryOptions['values'],
					$this->_queryOptions['fields']
				);
		return $this->returnDoResult($ajaxReturn);
	}

	private function returnDoResult($ajaxReturn = true)
	{
		if($this->doResult){
			if($ajaxReturn)
				$this->ajaxReturn('操作成功'); 
			return true;
		}else{
			if(!$ajaxReturn)
				return false;

			$messageStr = null;
			foreach(parent::getMessages() as $message){
				$messageStr .= $message . '; ';
			}
			$this->ajaxReturn(rtrim($messageStr, '; '), false);
		}
	}

	protected function ajaxReturn($data, $success=true){
		$Controller = new Controller();
		$Controller->ajaxReturn($data, $success);
	}

}
