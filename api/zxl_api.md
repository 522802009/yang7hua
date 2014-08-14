**一、测试帐号**

   * 测试地址： http://dev.zhengxinla.com

   * 会员帐号： yiqihao  yiqihao 
   
   * 管理员帐号： admin admin

------

**二、公共API**    

   * 验证码 /public/captcha                                                                                                                                                                                              
			

------  
**三、会员相关**

   * 查询 /search/query
        
        * 请求方式: POST     
            :---      	|   :---    |   :---	|       :---
            参数名		|   类型   	|   必需  	|    说明
            idcard    	|   string  |   是(searchtype为1时必需)   |  身份证号    
            realname  	|   string  |   是(searchtype为1时必需)   |  真实姓名   
            reason    	|   int     |   是(searchtype为1时必需)   |  查询原因(1-贷款审批, 2-担保审批)
            guarantee_name | string | 	是(searchtype为2时必需)    | 公司名称
            guarantee_no   | string | 	是(searchtype为2时必需)    | 组织机构代码证
            searchtype     | int    | 	是      	|    查询方式(1-个人查询, 2-公司查询) 
            
        * 返回结果: JSON
            --- 个人查询 ---
            {
                "ret": 1,
                "data": {
                    "searchinfo": {
                        "searchtype": 1,
                        "cardtype": "身份证",
                        "cardnumber": "420528198709081333",
                        "realname": "杨华",
                        "operator": "yiqihao",
                        "reason": "贷款审批"
                    },
                    "loanlist": [
                        {
                            "lid": "101645",
                            "companyid": "1",
                            "amount": "27800",
                            "deadline": "4",
                            "deadline_type": "m",
                            "status": "41",
                            "addtime": "2013-07-28",
                            "is_auto": "1"
                        },
                        ...
                    ],
                    "company": {
                        "1": "武汉一起好信息",
                        "5": "武汉一起好"
                    },
                    "totalinfo": {
                        "legal_agency_num": 2,
                        "agency_num": 2,
                        "loan_num": 7,
                        "amount": 149000,
                        "remain_amount": 0,
                        "six_remain_amount": 0
                    },
                    "guarantee": [
                        {
                            "companyid": "5",
                            "amount": "10000",
                            "repay_method": "e",
                            "addtime": "2014-07-25",
                            "companyname": "武汉一起好",
                            "is_auto": "0",
                            "status": "99"
                        },
                        ...
                    ],
                    "guarantee_info": {
                        "amount": 40000,
                        "count": 2
                    },
                    "reason": {
                        "1": "贷款审批",
                        "2": "担保审批"
                    },
                    "record": {
                        "list": [
                            {
                                "record_id": "1491",
                                "addtime": "2014-08-11",
                                "reason": "1",
                                "operator": "yiqihao"
                            },
                            ...
                        ]
                    }
                }
            }

            --- 公司查询 ---
            JSON:
                --- 成功 ---
                {
                    "ret": 1,
                    "data": {
                        "loanlist": [
                            {
                                "companyid": "5",
                                "amount": "10000",
                                "repay_method": "e",
                                "addtime": "2014-07-24",
                                "companyname": "武汉一起好",
                                "is_auto": "0",
                                "status": "41"
                            },
                            ...
                        ],
                        "guarantee_info": {
                            "amount": 330000,
                            "count": 4
                        },
                        "searchinfo": {
                            "searchtype": 2,
                            "guarantee_name": "武汉一起好",
                            "guarantee_no": "12345678-f"
                        },
                        "record": {
                            "list": [
                                {
                                    "record_id": "1436",
                                    "addtime": "2014-08-08",
                                    "reason": "0",
                                    "operator": "yiqihao"
                                },
                                ...
                            ]
                        }
                    }
                }           
                


   * 登录 /member/login
        
        * 请求方式: POST
            :---      	|   :---    |   :---	|       :---
            参数名    	|   类型   	|   必需    	|   说明
            username 	|  	string  |   是      	|   登录帐号
            password 	|  	string  |   是      	|   登录密码
            captcha  	|  	string  |   是      	|   验证码
            
        * 返回结果: JSON
            --- 失败 ---
            {
                "ret": 0,
                "msg": "账号或密码不正确"
            }
            --- 成功 ---
            {
                "ret": 1,
                "msg": "登陆成功"
            }


    
   * 贷款列表 /loan/list

        * 请求方式: POST
            :---     	|    :---   |   :---	|       :---
            参数名    	|    类型   	|   必需    	|   说明
            p   		|    int   	|   否      	|   页码   
            limit    	|    int   	|   否      	|   条/页
            
        * 返回结果: JSON
            --- 成功 ---
            {
                "ret": 1,
                "data": {                
                    "loan": {
                        "list": [
                            {
                                "id": "92",
                                "status": "51",
                                "title": "",
                                "is_auto": "0",
                                "amount": "543",
                                "deadline": "4",
                                "repay_method": "m",
                                "addtime": "2014-08-07",
                                "realname": "rtete",
                                "idcard": "456123330000000001"
                            },
                           ...
                        ],
                        "page": {
                            "size": 10,
                            "count": 35,
                            "total": 4,
                            "now": 1,
                            "prev": 0,
                            "next": 2,
                            "up": 1,
                            "down": 4,
                            "list": [
                                1,
                                2,
                                3,
                                ...
                            ]
                        }
                    }
                }
            }
    
       

        