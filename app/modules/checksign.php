<?php
class checksignModules extends Modules
{

		//验证签名  GET字符串 、入参字符串
		public function checksafe()
		{
			$uri = $_GET;
			$data	= $_GET["data"]; //优先取用GET方式
			if($data){
				 $data  = urldecode($data); //对GET的DATA做URL解码处理
			}else{
				 $data	= $_POST["data"];   //GET参数不存在时取data方法
			}
			$this->addlogs("url:".json_encode($_GET));
			$this->addlogs("data:".$data);
			if(!is_array($uri)){ appError(400,"URI必填参数发生错误"); }
			if(is_null(json_decode($data,true))){ appError(400,"请求参数格式错误{JSON}"); }
			$appid = $uri["appid"];
			if($appid==""){ appError(400,"APPID参数错误"); }
			//验证用户授权许可
			$query = " SELECT * FROM ".DB_SYSTEM.".api_author WHERE appid = ?  ";
			$agrs = array($appid);
			$row = $this->db->getRow($query,$agrs);
			if(!$row){ appError(400,"错误，没有授权许可"); } //当没有查到授权ID时，返回
			//***组合正常的返回数据-开始
			$arr   = array();
			$arr["appid"] = $appid;
			$arr["source"]= ($uri["source"])?$uri["source"]:"springapi"; 	//接口来源处理
			$arr["data"]  = json_decode($data,true);					//接收的参数
			//***组合正常的返回数据-结合
			//***** 这里预留，可以设置IP白名单的方法访问
			//开始验签执行
			$sign  = $uri["sign"];				//加密参数
			if($sign==""){  appError(400,"");  }
			$datetime = $uri["dateline"]; //时间
			$dateline = strtotime($datetime);
			$expires  = time()-1800;//过期时长为半小时
			//if($dateline<$expires){ appError(400,"请求超时"); }
			//处理必填参数
			$secret = $row["secret"];
			//组装参数 md5(md5(json).dateline); json=appid+parm+secret
			$md5arr = $appid.$data.$secret;
			$nsign  = strtoupper(md5(md5($md5arr).$datetime));//进行验签加密并转大写
			if($sign!=$nsign){ appError(401,"验签发生错误"); }
			//验签成功，返回数据
			return $arr;

		}

		//日志函数
		public function addlogs($data="")
		{
		    $data = date("Y-m-d H:i:s")."||".$data."\n";
		    $fp = fopen("./logs/".date("Y-m-d").".logs.txt","a");
		    flock($fp, LOCK_EX) ;
		    fwrite($fp,$data);
		    flock($fp, LOCK_UN);
		    fclose($fp);
		    return 1;
		}



















}
?>
