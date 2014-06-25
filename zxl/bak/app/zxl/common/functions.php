<?php


function getMethodsOfClass($className)
{
	$class = new ReflectionClass($className);
	return $class->getMethods();
}
