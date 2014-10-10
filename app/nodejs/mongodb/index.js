var mongodb = require('mongodb');
var server = mongodb.Server('localhost', 27017);
var db = new mongodb.Db('test', server, {safe:true});

db.open(function(err, db){
	if (!err) {
		console.log('db connected.');
		db.collection('test', function(err, test){
			test.find().toArray(function(err, result){
				console.log(result);
			});
			test.findOne(function(err, result){
				console.log(result);
			});
		})
	} else {
		console.log(err);
	}
});
