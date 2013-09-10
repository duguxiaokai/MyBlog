<?php
class ItemAction extends Action{
	public function list_item(){
		$item=M("sys_item");
		$items=$item->where("type <> 'jianyan_buytype'")->order("type asc,orderlist")->select();
		$this->assign('items',$items);//
		$this->display();
	}
	function selectitem(){
		$item=M("sys_item");
		$Model = new Model();
		$data=$Model->query("select distinct typename from `sys_item` where type <> 'jianyan_buytype'");//查询记录去除重复的记录
		echo json_encode($data);
	}

	public function onchangeitem(){
		$itemtype=$_REQUEST['itemtype'];
		$item=M("sys_item");
		$data=$item->where("typename='".$itemtype."'")->select();
		echo json_encode($data);
	}
	public function newitem(){
		$itemtype=$_REQUEST['typename'];
		$item=M("sys_item");
		$type=$item->field("type")->where("typename='".$itemtype."'")->find();
		$this->assign('itemtype',$itemtype);
		$this->assign('type',$type);
		$this->display();
	}
	public function savenewitem(){
        //var_dump($_POST);die;
        $name=$_POST['name'];
        $type=$_POST['type'];
        $orgid=$_POST['orgid'];
        $typename=$_POST['typename'];
        $orderlist=$_POST['orderlist'];
        $showditui=$_POST['showditui'];
		$sql = "insert into sys_item (name,type,orgid,typename,orderlist,showditui)values('$name','$type','$orgid','$typename','$orderlist','$showditui')";
		//var_dump($sql);die;
        $Model=new Model();
        $data = $Model->query($sql);
		echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
	}
	function moredelitem(){
		$itemIds=$_REQUEST['itemIds'];
		if(isset($itemIds)){
			$item=M('sys_item')->delete($itemIds);
			if($item>0){
				 echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
			}
			else{
				echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
			}
		}
	}
	function edititem(){
		$id=$_GET["id"];
		$item=M('sys_item');
		$data=$item->getByid($id);
		$this->assign('data',$data);
        //var_dump($data);die;
		$this->display();

	}
	function saveedititem(){
		$id=$_POST['itemid'];
		$name=$_POST['name'];
		$type=$_POST['type'];
		$typename=$_POST['typename'];
        $orderlist=$_POST['orderlist'];
        $showditui=$_POST['showditui'];
		$sql="update sys_item set `name`='".$name."',`type`='".$type."',`typename`='".$typename."',`orderlist`='".$orderlist."',`showditui`='".$showditui."' where id=".$id;
		$Model = new Model() ;// 实例化一个model对象 没有对应任何数据表
        $data = $Model->execute($sql);
        if($data){
			 echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
		}
		else{
			 echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
		}
	}
}
?>
















