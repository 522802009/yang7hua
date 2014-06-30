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

function base64url_encode($data) { 
	return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
} 

function base64url_decode($data) { 
	return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
} 
