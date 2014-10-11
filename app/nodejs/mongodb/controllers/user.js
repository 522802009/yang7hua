exports.reg = function(req, res, next){
	if (req.method == 'GET') {
		res.render('user/reg');
	} else {
		res.send(req.body);
	}
}
