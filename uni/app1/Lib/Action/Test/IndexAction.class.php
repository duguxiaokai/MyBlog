<?php
header("content-type:text/html; charset=UTF-8"); 
include ("HttpClient.class.php");
//include ("HttpClient1.class.php");
class IndexAction extends Action{
	
	function index()
	{

			$paramss="content_and=宝马";
			$host="http://119.2.8.170:9999/search/search";
			echo $paramss.='&limit=50&begin_date=20130605';
			echo "<br />";
			//return;
			$content = HttpClient::quickPost($host,$paramss);  			
			$content=trim($content, chr(239) . chr(187) . chr(191));
			$rows=json_decode($content,true);
			$i=1;
			$html="";
			foreach($rows as $one)
			{
				$html.=$i.":<a target='_blank' href='".$one['url']."'>".$one['title']."</a><br />";
				$temp=$one["content"];
				$html.="<div>".$temp."</div><br />";	
				//echo $one['images'];
				$imgs=$one['images'];
				foreach($imgs as $img)
				{
					$html.="<img src='".$img."' /><br />";	
				}
				$i++;
			}
			echo $html;
			echo 'done!';
	}

}

?>