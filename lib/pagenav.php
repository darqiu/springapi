<?php
class pagenav
{

	Public $pageRows = 20;
	
	public function show($rowCount='0',$pageRows='0',$type='0',$maxpages='0')//总条数，每页记录数，分页类型，最大页数
    {
		//print_r($_GET);
		if($_GET)
		{
			$urlarray = array();
			ksort($_GET);
			foreach($_GET as $k => $v)
			{
				if($k=='page')continue;
				if($k=='mod')continue;
				if($k=='ac')continue;
				if(isset($_COOKIE[$k])&&$_COOKIE[$k]==$v)continue;
				if($k=="PHPSESSID")	continue;
				if($k=="URLARR")	continue;
				if($v<>""){
					$urlarray[]=urlencode($k).'='.urlencode($v);
				}
			}
			if($_GET["mod"]!="index"){
				$modac = $_GET["mod"];
				if($_GET["ac"]!="app"){
					$modac = $modac."/".$_GET["ac"];
				}
			}
			if($urlarray){ $urlarr = @implode("&",$urlarray)."&"; }
			$url = S_ROOT.$modac.'?'.$urlarr;
		}
		else
		{
			$url='?';
		}

		$pageNums=($_GET["page"])?$_GET["page"]:'1';

		if($type=='1'){
			return $this->numPage($rowCount,$pageRows,$pageNums,$maxpages);			//号码页码分页
		}elseif($type=='2'){
			return $this->ajaxPage($rowCount,$pageRows,$pageNums,$maxpages);	//普通前后分页
		}elseif($type=='3'){
			return $this->btnPage($rowCount,$pageRows,$pageNums,$url,$maxpages);	//普通前后分页
		}else{
			return $this->txtPage($rowCount,$pageRows,$pageNums,$url,$maxpages);	//普通前后分页
		}
		
	}



	public function btnPage($rowCount,$pageRows,$pageId,$url,$maxpages)
	{
		$pages = ceil($rowCount/$pageRows);
		if($pages==0){
			$pages=1;
		}
		if($pages<=1){
			//return false;
		}
		if($pages > $maxpages && $maxpages){
			$pages = $maxpages;
		} //判断最大分页数
		if(isset($pageId))
		{
			$head = ($pageId==1) ?"<span class=\"no\">首页</span>":"<a href=\"".$url."page=1\">首页</a>";
			$up =	($pageId==1) ?"<span class=\"no\">上页</span>":"<a href=\"".$url."page=".intval($pageId-1)."\">上页</a>";
			$down = ($pageId==$pages)?"<span class=\"no\">下页</span>":"<a href=\"".$url."page=".intval($pageId+1)."\">下页</a>";
			$foot = ($pageId==$pages)?"<span class=\"no\">尾页</span>":"<a href=\"".$url."page=".$pages."\">尾页</a>";
			$offset = ($pageId-1)* $pageRows;
		}else{
			$pageId = 1;
			$head = "<span class=\"no\">首页</span>";
			$up = "<span class=\"no\">上页</span>";
			$down = ($pageId==$pages)?"<span class=\"no\">下页</span>":"<a href=\"".$url."page=".intval($pageId+1)."\">下页</a>";
			$foot = ($pageId==$pages)?"<span class=\"no\">下页</span>":"<a href=\"".$url."page=".$pages."\">尾页</a>";
			$offset = ($pageId-1)* $pageRows;
		}
		$pageNav = "".$head."".$up."".$down."".$foot."".$pageseles;
		return $pageNav;
	}
	
	//+------------------------------------------------------------------------------------------------------------
	  //Desc:$rowCount记录总条数、$pageRows每页显示的记录条数、当前页面、$url当前分页的网址
	  
	public function txtPage($rowCount,$pageRows,$pageId,$url,$maxpages)
	{		
		$pages = ceil($rowCount/$pageRows);
		if($pages==0){
			$pages=1;
		}
		if($pages<=1){
			//return false;
		}
		if($pages > $maxpages && $maxpages){
			$pages = $maxpages;
		} //判断最大分页数
		if(isset($pageId))
		{
			$head = ($pageId==1) ?"<span class=\"no\">首页</span>":"<a href=\"".$url."page=1\">首页</a>";
			$up =	($pageId==1) ?"<span class=\"no\">上页</span>":"<a href=\"".$url."page=".intval($pageId-1)."\">上页</a>";
			$down = ($pageId==$pages)?"<span class=\"no\">下页</span>":"<a href=\"".$url."page=".intval($pageId+1)."\">下页</a>";
			$foot = ($pageId==$pages)?"<span class=\"no\">尾页</span>":"<a href=\"".$url."page=".$pages."\">尾页</a>";
			$offset = ($pageId-1)* $pageRows;
		}else{
			$pageId = 1;
			$head = "<span class=\"no\">首页</span>";
			$up = "<span class=\"no\">上页</span>";
			$down = ($pageId==$pages)?"<span class=\"no\">下页</span>":"<a href=\"".$url."page=".intval($pageId+1)."\">下页</a>";
			$foot = ($pageId==$pages)?"<span class=\"no\">下页</span>":"<a href=\"".$url."page=".$pages."\">尾页</a>";
			$offset = ($pageId-1)* $pageRows;
		}
		$pageseles = "<input type='hidden' id='pagenav_maxpage' value='".$pages."'>";
		$pageseles.= "<input type='hidden' id='pagenav_urlto' value='".$url."'>";
		$pageseles.= "<input type='text' id='pagenav_page' value='".$pageId."' style='width:26px;text-align:center;line-height:16px;height:16px; 'onkeydown=\"if(event.keyCode==13)pagedo();\"><span class=\"no\">".$pages."</span>";
		//$pageNav = "".$head."".$up."".$down."".$foot."<span class=\"nums\">".$pageId."/".$pages."</span>";
		$pageNav = "".$head."".$up."".$down."".$foot."".$pageseles;
		return $pageNav;
	}


	//+------------------------------------------------------------------------------------------------------------
	  //Desc:$rowCount记录总条数、$pageRows每页显示的记录条数、当前页面、$url当前分页的网址
	  
	public function ajaxPage($rowCount,$pageRows,$pageId,$maxpages)
	{		
		$pages = ceil($rowCount/$pageRows);
		if($pages==0){
			$pages = 1;
		}
		if($pages<=1){
			//return false;
		}
		if($pages>$maxpages && $maxpages){
			$pages = (int)$maxpages;
		} //判断最大分页数
		if(isset($pageId))
		{
			$head = ($pageId==1) ?"<span class=\"no\">首页</span>":"<a href=\"javascript:void(0);\" onclick=\"page(1)\">首页</a>";
			$up =	($pageId==1) ?"<span class=\"no\">上页</span>":"<a href=\"javascript:void(0);\" onclick=\"page(".intval($pageId-1).")\">上页</a>";
			$down = ($pageId==$pages)?"<span class=\"no\">下页</span>":"<a href=\"javascript:void(0);\" onclick=\"page(".intval($pageId+1).")\">下页</a>";
			$foot = ($pageId==$pages)?"<span class=\"no\">尾页</span>":"<a href=\"javascript:void(0);\" onclick=\"page(".$pages.")\">尾页</a>";
			$offset = ($pageId-1)* $pageRows;
		}else{
			$pageId = 1;
			$head = "<span class=\"no\">首页</span>";
			$up = "<span class=\"no\">上页</span>";
			$down = ($pageId==$pages)?"<span class=\"no\">下页</span>":"<a href=\"javascript:void(0);\" onclick=\"page(".intval($pageId+1).")\">下页</a>";
			$foot = ($pageId==$pages)?"<span class=\"no\">下页</span>":"<a href=\"javascript:void(0);\" onclick=\"page(".$pages.")\">尾页</a>";
			$offset = ($pageId-1)* $pageRows;
		}
		$pageNav = "".$head."".$up."".$down."".$foot."<span class=\"nums\">".$pageId."/".$pages."</span>";
		//$pageNav = "".$head."".$up."".$down."".$foot;
		return $pageNav;
	}

	public function numPage($rowCount,$pageRows,$pageId,$url,$maxpages)//总条数，每页记录数，当前页, URL链接、最大分页数
	{
		$multipage = "";

		$page=10;
		$offset= 4;
		$pages = ceil($rowCount/$pageRows);
		if($pages<=1){
			//return false;
		}
		if($pages > $maxpages && $maxpages){
			$pages = $maxpages;
		} //判断最大分页数
		$from = $pageId - $offset;
		$to = $pageId + $page - $offset - 1;
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			if($from < 1) {
				$to = $pageId + 1 - $from;
				$from = 1;
				if(($to - $from) < $page && ($to - $from) < $pages)
				{
					$to = $page;
				}
			}elseif($to > $pages){
				$from = $pageId - $pages + $to;
				$to = $pages;
				if(($to - $from) < $page && ($to - $from) < $pages) {
					$from = $pages - $page + 1;
				}
			}
		}
		$multipage.= $pageId>1?"<a href=\"".$url."page=1\"><<</a>":"<span class=\"no\"><<</span>";
		for($i = $from; $i <= $to; $i++){
			if($i!=$pageId){
				$multipage.= "<a href=\"".$url."page=$i\">$i</a>";
			}else{
				$multipage.= "<span class=\"page\">".$i."</span>";
			}
		}
		$multipage.= $pages>$pageId?"<a href=\"".$url."page=$pages\">>></a>":"<span class=\"no\">>></span>";

		return $multipage;
	}

}
?>