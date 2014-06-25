<?php
namespace Common\Func;

use ReflectionClass;

function limit($p=1, $limit=10)
{
	return ($p-1)*$limit . ',' . $limit;
}

function password($password, $salt='zxl')
{
	return md5(md5($password . $salt));
}

function getMethodsOfClass($className)
{
	$class = new ReflectionClass($className);
	return $class->getMethods();
}
