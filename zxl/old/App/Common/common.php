<?php


/**
 * 输出json格式数据
 * @param [json|array|string] $data
 */
function export_json($data){
	if(!json_decode($data))
		$data = json_encode($data);
	
	header('Content-type:text/json');
	echo $data;
}


function password($str){
	return md5(md5($str));
}