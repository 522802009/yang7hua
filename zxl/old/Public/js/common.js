function page(){
	var pageObj = $(".pagination");
	this.click	= function(callback){		
		pageObj.delegate('a', 'click', function(){
			if($(this).hasClass('current'))
				return false;
			var p = $(this).attr('href').match(/\?p=(\d+)/)[1];
			//console.log(p);			
			callback(p);	
			return false;				
		})	
	};

	this.page = function(){			
		return pageObj.find('.current').text();
	};	

	return this;										
}

var config = {
	status : ['','已结清','还款中','逾期中'],
	memberstatus : ['','已通过','未通过','未审核']
};

var Common = {
	formatDate : function(microtime, showtime){
		var _today = new Date(microtime*1000);
		
		var date = _today.getFullYear()+'-'+(_today.getMonth()+1)+'-'+_today.getDate(),
			time = _today.getHours()+':'+_today.getMinutes();

		return showtime ? date+' '+time : date;
	},

	formatStatus : function(status){
		return config.status[status];
	},

	formatMemStatus : function(status){
		return config.memberstatus[status];
	},

	formatMoney : function() {
	    var args = arguments;
	    var money = args[0];
	    if (!money)
	        money = 0;
	    if (typeof(money) != "string")
	        money = money.toString();
	    var line = "";
	    if (money.substr(0, 1) == "-") {
	        line = "-";
	        money = money.substr(1);
	    }

	    var num = args[1] || 2;
	    var width = args[2] || 3;
	    var flag = args[3] || ",";
	    
	    var moneyArr = money.split(".");
	    var moneyLeft = moneyArr[0];
	    var len = moneyLeft.length;
	    var moneyRight = moneyArr[1] || "";
	    if (moneyRight != "") {
	        while(moneyRight.length < num)
	            moneyRight += "0";
	        moneyRight = moneyRight.substr(0, num);
	    }
	    
	    var i = len % width;
	    if (i == 0)
	        money = "";
	    else
	        money = moneyLeft.substr(0, i)+flag;
	    for (; i < len; i += width) {
	        money += moneyLeft.substr(i, width)+flag;
	    }
	    money = money.substr(0, money.length-1);
	    if (moneyRight != "")
	        money += "."+moneyRight;
	    return line+money;
	}
}


function reloadCaptcha(){
	$('.captcha').attr('src', "/public/captcha?w=80&h=32&v="+Math.random());
}

$(function(){
	$('.captcha').click(reloadCaptcha);
})

function resCaptcha(errmsg){
	if(errmsg.indexOf('验证码') != -1){
		reloadCaptcha();
	}
}