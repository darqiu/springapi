<?php
/**
+------------------------------------------------------------------------------
* Spring Framework 常用
+------------------------------------------------------------------------------
* @Date		2008-02-20
* @QQ		28683
* @Author	Jeffy (darqiu@gmail.com)
* @version	2.0
+------------------------------------------------------------------------------
*/
class plugin
{

	public function Service()
	{
	}

	//+----------------------------------------------------------------------------------------------------------
	  //Desc:取得域名
	public function getDomain()
	{
			$S = plugin::getURL();
			$S = parse_url($S);
			$S = strtolower($S['host']) ; //取域名部分
			//echo $S;
			$domain = array('com','cn','name','org','net'); //域名后缀 有新的就扩展这吧
			$SS = $S;
			$dd = implode('|',$domain);
			$SS = preg_replace('/(.('.$dd.'))*.('.$dd.')$/iU','',$SS);
			$SS = explode('.',$SS);
			$SS = array_pop($SS);  //取最后的主域名
			$SS = substr($S,strrpos($S,$SS));  //加上后缀拼成完成的主域名
			return $SS;
	}

	//+----------------------------------------------------------------------------------------------------------
	  //Desc:取得域名
	public function getHost()
	{
			return $_SERVER['HTTP_HOST'];
	}

	//+----------------------------------------------------------------------------------------------------------
	  //Desc:取得当前主机头
	public function subDomain()
	{
			$serverhost = explode('.',$_SERVER["HTTP_HOST"]);$subdomain = $serverhost[0];
			if (isset($serverhost[2]) and ($serverhost[2] != "") ? $sub = $subdomain : $sub = "" );
			return $sub;
	}

	//+----------------------------------------------------------------------------------------------------------
	  //Desc:取得来源页面
	public function retURL()
	{
			$referer = $_SERVER['HTTP_REFERER'];
			$urlstr = str_replace("http://","",$referer);
			$urlstr = str_replace("https://","",$urlstr);
			$str_array = explode("/",$urlstr);
			if($str_array[0] == plugin::getHost())
			{
				return str_replace(plugin::getHost(),"",$urlstr);
			}else{
				return $referer;
			}
	}

	//+----------------------------------------------------------------------------------------------------------
		//Desc:显示当前文件页面位置绝对路径
	public function getURL()
	{
			//return "http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
			//return "http://".$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI'];
			return $_SERVER['REQUEST_URI'];
	}

	//+----------------------------------------------------------------------------------------------------------
		//Desc:显示当前文件页面位置路径
	public function getPatch()
	{
			//return "http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?".$_SERVER["QUERY_STRING"];
			$urlto = $_SERVER['REQUEST_URI'];
			return $urlto;
	}

	//+----------------------------------------------------------------------------------------------------------
    //Desc:取得当前文件的物理路径
	public function getFilePatch()
	{
			$urlto = str_replace(strtolower(trim(substr(strrchr($_SERVER["SCRIPT_FILENAME"], '/'),1))),"",$_SERVER["SCRIPT_FILENAME"]);
			return $urlto;
	}

	//+----------------------------------------------------------------------------------------------------------
	//Desc:获得浏览器信息及版本号
	public function getAgent()
	{
			//MSIE //Chrome //Safari //Firefox //Opera
			$agent = explode(" ",$_SERVER['HTTP_USER_AGENT']);
			$nums  = (int)COUNT($agent)-1;
			$str = explode("/",$agent[$nums]);
			return $str;
	}

	//+----------------------------------------------------------------------------------------------------------
	//Desc:获取用户IP地址
	public function getIP()
	{
			if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
			{
					$onlineip = getenv('HTTP_CLIENT_IP');
			}
			elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
			{
					$onlineip = getenv('HTTP_X_FORWARDED_FOR');
			}
			elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
			{
					$onlineip = getenv('REMOTE_ADDR');
			}
			elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
			{
					$onlineip = $_SERVER['REMOTE_ADDR'];
			}
			return $onlineip;
	}


	//+----------------------------------------------------------------------------------------------------------
	//Desc:生成随机数
	public function random($length){ //(生成长度)

			$hash = '';
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
			$max = strlen($chars) - 1;
			mt_srand((double)microtime() * 1000000);
			for($i = 0; $i < $length; $i++) {
					$hash .= $chars[mt_rand(0, $max)];
			}
			return $hash;
	}

	//+----------------------------------------------------------------------------------------------------------
	//Desc:针对二维数组排序
	public function sortArr($array,$keys,$type='asc'){

	    if(!isset($array) || !is_array($array) || empty($array)){
	        return '';
	    }
	    //排序字段名，如：id
	    if(!isset($keys) || trim($keys)==''){
	        return '';
	    }
	    //排序方式，如：desc、asc
	    if(!isset($type) || $type=='' || !in_array(strtolower($type),array('asc','desc'))){
	        return '';
	    }
	    //定义一个数组
	    $keysvalue=array();
	    foreach($array as $key=>$val){
	        //对排序字段值进行过滤
	        $val[$keys] = str_replace('-','',$val[$keys]);
	        $val[$keys] = str_replace(' ','',$val[$keys]);
	        $val[$keys] = str_replace(':','',$val[$keys]);

	        //将记录中指定的键名放入数组中，如：[0]=>5,[1]=>3,[2]=>6
	        $keysvalue[] =$val[$keys];//排序字段，如：id         索引=》排序键名
	    }
	    asort($keysvalue); //按值升序排序，且保持键名与键值之间的索引关系,如：[1]=>3,[0]=>5,[2]=>6
	    reset($keysvalue); //指针重新指向数组第一个
	    foreach($keysvalue as $key=>$vals) {
	        $keysort[] = $key;//0=>[1],1=>[0],2=>[2]
	    }
	    $keysvalue = array();
	    $count=count($keysort);//排序记录数
	    if(strtolower($type) != 'asc'){//降序
	        for($i=$count-1; $i>=0; $i--) {
	            $keysvalue[] = $array[$keysort[$i]];
	        }
	    }else{//升序
	        for($i=0; $i<$count; $i++){
	            $keysvalue[] = $array[$keysort[$i]];
	        }
	    }
	    return $keysvalue;
	}

	//+----------------------------------------------------------------------------------------------------------
	//Desc:获取指定日期所在月的第一天和最后一天
	public function getTheMonth($date){
		$firstday = date("Y-m-01",strtotime($date));
		$lastday = date("Y-m-d",strtotime("$firstday +1 month -1 day"));
		return array($firstday,$lastday);
	}

	//+----------------------------------------------------------------------------------------------------------
	//Desc:获取指定日期上个月的第一天和最后一天
	public function getPurMonth($date){
			$time=strtotime($date);
			$firstday=date('Y-m-01',strtotime(date('Y',$time).'-'.(date('m',$time)-1).'-01'));
			$lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
			return array($firstday,$lastday);
	}

	//+----------------------------------------------------------------------------------------------------------
	//Desc:取得天数时间分秒
	public function getTime($val="0"){
			if($val>0){
					$arr = array();
					$arr['d']  = (int)($val/(3600*24));
					$arr['h']  = (int)($val%(3600*24)/3600);
					$arr['i']  = (int)($val%(3600*24)%3600/60);
					$arr['s']  = (int)($val%(3600*24)%3600%60);
			}
			return $arr ;
	}

	//+----------------------------------------------------------------------------------------------------------
	//Desc:计算天数的多少年多少月多少日
	public function datediff($StartTime,$EndTime=""){
			if(empty($StartTime)){
				return false;
			}
			if(empty($EndTime)){
				$EndTime=date("Y-m-d");
			}
			$StartStamp=strtotime($StartTime);	//把开始字符串时间转换成时间戳
			$EndStamp=strtotime($EndTime);			//把结束字符串时间转换成时间戳
			if($StartStamp>$EndStamp){					//如果开始时间大于结束时间 则交换两个时间
					$temp				=	$StartStamp;
					$StartStamp	=	$EndStamp;
					$EndStamp		=	$temp;
			}
			$ReturnYears	=	0;//相差的年数
			$ReturnMonth	=	0;//相差的月数
			$ReturnDays		=	0;//相差的天数
			for($i=1;strtotime("+$i years",$StartStamp)<=$EndStamp;	$i++) $ReturnYears++;						//求相差的年数
			for($i=1;strtotime("+$ReturnYears years $i month",$StartStamp)<=$EndStamp;$i++) $ReturnMonth++;		//求相差的月数
			for($i=1;strtotime("+$ReturnYears years $ReturnMonth month $i days",$StartStamp)<=$EndStamp;$i++) $ReturnDays++;//求相差的天数
			$retarr=array("y"=>$ReturnYears,"m"=>$ReturnMonth,"d"=>$ReturnDays);								//封装成数组
			return $retarr;
	}
	//+----------------------------------------------------------------------------------------------------------
	//Desc:字符串截取
	public function cutstr($sourcestr,$cutlength=10,$dot='..')
	{
			$returnstr='';
			$i=0;
			$n=0;
			$sourcestr = trim($sourcestr);
			$str_length = strlen($sourcestr);//字符串的字节数
			while (($n<$cutlength) and ($i<=$str_length))
			{
				$temp_str=substr($sourcestr,$i,1);
				$ascnum=Ord($temp_str);//得到字符串中第$i位字符的ascii码
				if ($ascnum>=224)    //如果ASCII位高与224，
				{
					$returnstr=$returnstr.substr($sourcestr,$i,3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
					$i=$i+3;            //实际Byte计为3
					$n++;            //字串长度计1

				}elseif ($ascnum>=192){ //如果ASCII位高与192，

					$returnstr=$returnstr.substr($sourcestr,$i,2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
					$i=$i+2;            //实际Byte计为2
					$n++;            //字串长度计1

				}elseif($ascnum>=65 && $ascnum<=90 && $ascnum>=97 && $ascnum<=122){ //如果是大写字母或者是小写字母

					$returnstr=$returnstr.substr($sourcestr,$i,2);
					$i=$i+2;            //实际的Byte数仍计1个
					$n++;				//但考虑整体美观，大写字母计成一个高位字符

				}else{					//其他情况下，半角标点符号，

					$returnstr=$returnstr.substr($sourcestr,$i,1);
					$i=$i+1;            //实际的Byte数计1个
					$n=$n+0.5;        //半角标点等与半个高位字符宽...
				}
			}
			if($str_length>$i){
				$dotlen = strlen($dot);
				if($dotlen){
					$dotlen = ceil($dotlen/2);
					$strnum = $cutlength-$dotlen;
					$returnstr = plugin::cutstr($returnstr,$strnum,"");
				}
				$returnstr = $returnstr.$dot;//超过长度时在尾处加上省略号
			}
			return $returnstr;
	}

	//+----------------------------------------------------------------------------------------------------------
	//Desc:格式化参数为数组
	public function extstr($str) //格式如 brandid=1&name=1&id=1
	{
		parse_str($str,$arr);
		return $arr;

		$str=explode('&',$str);
		$newstr = array();
		foreach($str as $item)
		{
			$item = explode('=',$item);
			$newstr[$item[0]] = $item[1];
		}
		return $newstr;
	}

	//+----------------------------------------------------------------------------------------------------------
	//Desc:隐藏数字字段
	public function hidenums($str="")
	{
			$IsWhat = preg_match('/(0[0-9]{2,3}[\-]?[2-9][0-9]{6,7}[\-]?[0-9]?)/i',$str); //固定电话
			if($IsWhat == 1)
			{
				return preg_replace('/(0[0-9]{2,3}[\-]?[2-9])[0-9]{3,4}([0-9]{3}[\-]?[0-9]?)/i','$1****$2',$str);
			}
			else
			{
				return  preg_replace('/(1[3458]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$str);
			}
	}

	//+----------------------------------------------------------------------------------------------------------
	//Desc:数组处理成数字符
	public function arrtostr($str)
	{
			if(is_array($str)){
					$arr = "";
					foreach($str AS $n=>$v){
						$arr.= $n."：".$v."\n";
					}
					$str = $arr."-------------------------\n";
			}
			return $str;
	}

	//+----------------------------------------------------------------------------------------------------------
	//Desc:数据安全组合
	public function buildsafe($paramArr,$sessid="bcee4596c3ad25a509874d5fb52046e8")
	{
			$sign = "";
			ksort($paramArr);
			foreach($paramArr AS $key=>$val){
					if($key!=""&&$val!=""){
							$sign.=$key.$val;
					}
			}
			$sign = strtoupper(md5($sessid.$sign.$sessid));
			return $sign;
	}

	public function removeBOM($str = '')
	{
	    if (substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf))
	        $str = substr($str, 3);
	    return $str;
	}

	//+----------------------------------------------------------------------------------------------------------
	//Desc:判断邮件格式
	public function isEmail($email){
		return strlen($email)>6&&preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/",$email);
	}

	//+----------------------------------------------------------------------------------------------------------
	//Desc:处理textareaHTML
	public function textarea($string)
	{
		return ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\" target=\"_blank\">\\0</a>",str_replace(" ","&nbsp;",str_replace(chr(10), "<br>",strip_tags($string))));
	}

}
?>
