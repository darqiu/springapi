<?php
/**
+------------------------------------------------------------------------------
* Spring Framework 架构配置
+------------------------------------------------------------------------------
* @date		2011-01-17
* @QQ		28683
* @Author	Jeffy (darqiu@gmail.com)
* @version	2.0
+------------------------------------------------------------------------------
*/

//载入工厂资源
require_once(MVC."action.php");
require_once(MVC."modules.php");
require_once(LIB."plugin.php");

//工厂设置实始化
$allfunc = array();

//数据库库名
define('DB_SYSTEM'	,"juran_system"	);	//DB_SYSTEM
define('DB_JURAN'		,"juran");					//DB_JURAN
define('DB_MEMBERS'	,"members"	);			//DB_MEMBERS
define('DB_MARKET'	,"market"	);			//DB_MARKET

//COOKIE扩展
define('COOKIE_PRE','_');

//系统数据库
$db = getFunc("dbpdo");
$db->dbType='mysql';
$db->connectNum='yws';
$db->configFile = CONFIG."db.config.php";	//亿家网站 数据库配置
$allfunc["db"]=$db;
//日志数据库
$odb = getFunc("dbpdo");
$odb->dbType='mysql';
$odb->connectNum='odb';
$odb->configFile = CONFIG."db.config.php";	//数据库配置
$allfunc["odb"]=$odb;

//COOKIE
$cookie = getFunc("cookie");
$cookie->cookiePre = COOKIE_PRE;
$allfunc["cookie"]=$cookie;

//错误及警告返回
function appError($code="404",$msg="请求接口发生错误")
{
		$arr		= array();
		$code		= ($code)?$code:"200";
		//状态字符段格式化整数
		$arr["code"]		= (int)$code;
		//判断是否数组并处理数据
		$arr["data"]		= array();
		//返回的信息内容
		$arr["msg"]			= $msg;
		//$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
		//header($protocol." ".$code);
		header("Content-type: application/json");
		echo json_encode($arr);//JSON_UNESCAPED_UNICODE
		exit;
}

//成功返回函数
function rejson($code="200",$data="",$msg="请求成功")
{
		$arr		= array();
		$code		= ($code)?$code:"200";
		//状态字符段格式化整数
		$arr["code"]		= (int)$code;
		//判断是否数组并处理数据
		$arr["data"]		= is_array($data)?$data:array($data);
		//返回的信息内容
		$arr["msg"]			= $msg;
		//$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
		//header($protocol." ".$code);
		header("Content-type: application/json");
		echo json_encode($arr);//JSON_UNESCAPED_UNICODE
		exit;
}

?>
