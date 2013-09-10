<?php
class RoleAction extends CommonAction{


	public function add_role() {
		$dept = M("sys_dept");
		$data=$dept->field('name')->select();
		$this->assign('data',$data);
		$this->display();
	}

	public function insertrole() {
		//var_dump($_POST);die;
		$role = M('sys_role');
		$data = $role->create();
	    if(!empty($data)){
			if(false !== $role->add()){
				echo "<script>alert('添加成功！');window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
            }else{
                echo "<script>alert('添加失败！');window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
            }
		}else {
			echo "<script>alert('添加失败');window.parent.g_pop.close(); </script>";
		}
	}

	public function list_role() {
		$role = M('sys_role');
    	$data = $role->select();
    	//var_dump($data);
    	$this->assign('data',$data);
    	$this->display('list_role');
	}

	public function showonerole() {
		$id=$_GET["id"];
    	$role = M('sys_role');
		$data=$role->getByid($id);
		$this->assign('data',$data);
		$this->display('showonerole');
	}

	public function editonerole() {
		$id=$_GET["id"];
    	if(!empty($id)){
			$role = M('sys_role');
			$data=$role->getByid($id);
			//var_dump($data);die;
			if(!empty($data)) {
				$dept = M('sys_dept');
				$data2 = $dept->field('name')->select();
				$this->assign('data2',$data2);
				$this->assign('data',$data);
				$this->display('editonerole');
			}else {
			    echo  "<script>alert('没有此用户信息');history.back();</script>";
			}
		}else {
			echo "<script>alert('编辑的内容不存在');history.back();</script>";
		}
	}

	public function saveeditrole() {
		$id = $_POST['id'];
    	$role=M('sys_role');
    	$data = $role->create();
    	//var_dump($data);die;
		if(!empty($data)){
			//var_dump($cus->save($data));die;
			if($role->save()){
				echo "<script>alert('修改成功！');</script>";
				/*echo "<script>window.close();</script>";*/
				$this->redirect('Role/list_role',0,0,'页面跳转中，请稍候~');
			}else {
                $this->redirect('Role/list_role',0,0,'页面跳转中，请稍候~');
				/*echo "<script>alert('没有修改，保存失败');history.go(-1);</script>";*/
			}
		}else {
			echo "<script>alert('表单数据有误');history.go(-1);</script>";
		}
	}
	
	public function deltrole()
	{
		$id = $_REQUEST['id'];
		$sql = "delete from sys_role where id=".$id;
		$m = new Model();
		$m->execute($sql);
	}

}
?>