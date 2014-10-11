//博客相关信息

var db = global.db;

var schema = new db.Schema({
	name : {type:String, default:''},
	value : {type:String, default:''}
});
schema.index({name:1});

var model = db.model('info', schema);

exports.findByName = function(name, callback){
	if (!name) {	
		callback('no name input.', null)
		return;
	}
	model.findOne({name:name}, function(err, row){
		callback(err, row);
	})
};

exports.getValue = function(name, callback){
	exports.findByName(name, function(err, row){
		if (!err && row)
			callback(null, row.value);
		else
			callback(err, null);
	});
}

exports.update = function(name, value, callback){
	if (!name || !value) {
		callback('参数错误', null);
		return;
	}
	model.update({name:name}, {$set:{value:value}}, {upsert:true}, function(err, docs){
		callback(err, docs);
	});
};
