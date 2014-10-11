exports.captcha = function(req, res, next){
	var ccap = require('ccap');
	/*
	var captcha = ccap({
		width : 80,
		height : 20,
		quality : 100,
		fontSize : 12
	}).get();
	console.log(captcha[0]);
	res.end(captcha[1]);
	*/
	console.log(ccap)
	res.end();
}
