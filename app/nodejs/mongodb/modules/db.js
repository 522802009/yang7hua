var config = global.config;
/*var mongodb = require('mongodb');
var server = new mongodb.Server(config.db_host, config.db_port, {auto_reconnect:true});
var db = new mongodb.Db(config.db_name, server, {safe:true});

exports.load = function(callback){
	db.open(function(err, db){
		callback(db);
	});
}
*/

var mongoose = require('mongoose');
var Schema = mongoose.Schema;
mongoose.connect(config.db_connect);

exports.model = function(model, schema){
	return mongoose.model(model, schema);
}

exports.mongoose = mongoose;
exports.Schema = Schema;
