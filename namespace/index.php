<?php
require 'base.php';
//echo __NAMESPACE__;

/*function call()
{
	echo 'Func call !';
}

namespace\call();
*/

//use \Base;

class Index extends \Base\BaseClass
{

	public function __construct()
	{
		echo 'Index Class !';
		parent::__construct();
	}

}

$c = new Index;
