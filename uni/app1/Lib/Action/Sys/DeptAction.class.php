<?php
class DeptAction extends CommonAction{

	function add_dept() {
		$src=$_REQUEST['src'];
		$dept=M('sys_dept');
		$data = $dept->field('name')->select();
		$this->assign('data',$data);
		$this->assign('src',$src);
		$this->display('add_dept');
	}

	function insertdept() {
		$dep=M('sys_dept');
		$data=array(
       		'owner'=>$_SESSION['name'],
			'name'=>$_POST['name'],
			'parent'=>$_POST['parent'],
			'type'=>$_POST['type'],
			'leader'=>$_POST['leader']
		);
		$depadd=$dep->add($data);
		if($depadd>0){
			echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
		}
		else{
			echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
		}

		/*$dept = M('sys_dept');
		$data = $dept->create();
	    if(!empty($data)){
			if(false !== $dept->add()){
				$this->success('数据添加成功！');
            }else{
                $this->error('数据写入错误');
            }
		}else {
			header("Content-Type:text/html; charset=utf-8");
			exit($dept->getError().' [ <A HREF="javascript:history.back()">返 回</A> ]');
		}*/
	}

	/*public function list_dept() {
		$dept = M('sys_dept');
		$data = $dept->order("id desc")->select();
		$this->assign('data',$data);
    	$this->display();
	}*/

	public function showonedept() {
		$id=$_GET["id"];
    	$dept = M('sys_dept');
		$data=$dept->getByid($id);
		$this->assign('data',$data);
		$this->display('showonedept');
	}

	public function editonedept() {
		$id=$_GET["id"];
    	if(!empty($id)){
			$dept = M('sys_dept');
			$data=$dept->getByid($id);
			//var_dump($data);die;
			if(!empty($data)) {
                $name = $dept->where("id !='".$id."'")->field('name')->select();
		        $this->assign('name',$name);
				$this->assign('data',$data);
				$this->display('editonedept');
			}else {
			    echo  "<script>alert('没有此用户信息');history.back();</script>";
			}
		}else {
			echo "<script>alert('编辑的内容不存在');history.back();</script>";
		}
	}

	public function saveeditdept() {
		$id = $_POST['id'];
    	$dept=M('sys_dept');
    	$data = $dept->create();
		if(!empty($data)){
			if($dept->save()){
				echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
			}else {
				echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
			}
		}else {
			echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
		}
	}
	function moredeldep(){
		$depids=$_REQUEST['depids'];
		if(isset($depids)){
			$dep=M('sys_dept')->delete($depids);
			if($dep>0){
				echo "<script>alert('succeed');history.back()</script>";
			}
			else{
				echo "<script>alert('fail');history.back()</script>";
			}
		}
	}
	public function list_dept() {
        $this->redirect(__APP__.'/List/List/list2?param:table=sys_dept',array(),0,"");
    }

}
?>




