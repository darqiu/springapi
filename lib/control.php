<?php
/**
+------------------------------------------------------------------------------
* Spring Framework 工厂模式
+------------------------------------------------------------------------------
* @date		2011-01-17
* @QQ		28683
* @Author	Jeffy (darqiu@gmail.com)
* @version	2.0
+------------------------------------------------------------------------------
*/
//处理GET请求规则
$_GET["mod"] = (isset($_GET["mod"])&&!empty($_GET["mod"]))?$_GET["mod"]:"index";

//加载工厂配置
require_once(LIB."app.php");

//组件加载工厂
function getFunc($object)
{
		$objectfile = LIB.$object.".php";
		if(file_exists($objectfile)){
				require_once($objectfile);
				if(!class_exists($object)){
						$msg = "组件 $object 对象未找到";
						if(DEBUG){
								appError(400,$msg);
						}else{
								appError();
						}
				}
				return new $object();
		}else{
				if(DEBUG){
						appError(400,"ERROR：组件 $object 不存在");
				}else{
						appError();
				}
		}
}

//加载视图
function getAction($action)
{
		global $allfunc;
		if(!isset($action)){ $action="index"; }
		//加载视图
		$mod = ACTION.$action.".php";
		if(!file_exists($mod)){
				//$mod = ACTION."index.php";
				if(DEBUG){
						appError(400,"错误，页面不存在");
				}else{
						appError();
				}
		}
		require_once($mod);
		$classname = $action.'Action';
		if(!class_exists($classname)){
				//$classname = 'indexAction';
				if(DEBUG){
						appError(400,"控制器 $action 对象未找到");
				}else{
						appError();
				}
		}
		$mp = new $classname();
		foreach($allfunc AS $id=>$fun){
				$mp->$id = $fun;
		}
		return $mp;
}

//加载模块
function getModel($model)
{
		global $allfunc;
		//加载model,写入属性
		$mod = MODEL.$model.".php";
		if(!file_exists($mod)){
				if(DEBUG){
						appError(400,"模块[$model]不存在");
				}else{
						appError();
				}
		}
		require_once($mod);
		$classname = $model.'Modules';
		if(!class_exists($classname)){
				if(DEBUG){
						appError(400,"模块 $classname 对象未找到");
				}else{
						appError();
				}
		}
		$mp = new $classname();
		foreach($allfunc AS $id=>$fun){
				$mp->$id = $fun;
		}
		return $mp;
}

?>
