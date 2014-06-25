<?php

class PublicAction extends BaseAction{

	public function __construct(){

	}

	public function captcha(){
		import("ORG.Util.Image");

		$width = isset($_REQUEST['w']) ? $_REQUEST['w'] : 60;
		$height = isset($_REQUEST['h']) ? $_REQUEST['h'] : 25;

		Image::buildImageVerify(4,2,'PNG',$width,$height);
	}

}
