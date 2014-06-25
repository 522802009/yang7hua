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

class Model extends \Phf\Mvc\Model
{
	
	protected $db;

	private $_metadataMemory;

	private $_allowValidators = array('require', 'email', 'notin', 'in', 'number', 'regex', 'unique', 'stringlength');

	protected $_validators;

	protected function initialize()
	{
		global $di;
		$this->db = $di->get('db');
		$this->_metadataMemory = new MetaDataMemory;
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

	private function filter($data, $allowFields = '*')
	{
		$allFields = $this->getFields();
		$filter = false;
		if($allowFields == '*'){
			$fields = $allFields;
		}else if(is_string($allowFields)){
			$fields = $allowFields;
		}else if(is_array($allowFields)){
			if(is_array($allowFields[0])){
				$fields = $allowFields[0];
				$filter = isset($allowFields[1]) ? $allowFields[1] : false;
			}else if(count($allowFields) == 2){ 
				$fields = $allowFields;
				$filter = $allowFields[1];
			}else{
				$fields = $allowFields[0];
			}
		}
		
		if(is_string($fields) && strpos($fields, ','))
			$fields = explode(',', $fields);
		else 
			$fields = (array) $fields;
		if($filter){
			$fields = array_diff($allFields, $fields);
		}

		$return = array();

		$filter = function($row) use ($fields){
			foreach($row as $k=>$v){
				if(!in_array($k, $fields)){
					unset($row[$k]);
				}
			}
			return $row;
		};

		$toArray = function($row) use ($filter){
			$row = $row->toArray();
			return $filter($row);
		};
		if(count($data) > 1){
			foreach($data as $row)
				$return[] = $toArray($row);
		}else{
			$return = $toArray($data);
		}

		return $return;
	}

	protected function getFields()
	{
		return $this->_metadataMemory->getAttributes($this);
	}

	public function save($data=null, $whiteList=null)
	{
		$Controller = new Controller();
		if(parent::save() == false){
			$errmsg = '';
			foreach(parent::getMessages() as $message){
				$errmsg .= $message . '; ';
			}
			$Controller->ajaxReturn(rtrim($errmsg, '; '), false);
		}
		$Controller->ajaxReturn('操作成功');
	}

}
