exports.list = function(req, res, next){
	global.isAjax(req, function(){
		res.send('is ajax.');
	}, function(){
		res.send('is not ajax.');
	})
}
