<?php
class StaffAction extends CommonAction {
	public function _initialize(){
        header("Content-Type:text/html; charset=utf-8");
    }

    public function add_staff() {
		$src=$_REQUEST['src'];
		$this->assign('src',$src);
    	$this->display();
    }

    public function insertstaff() {
		$name=$_POST['name'];
		$gender=$_POST['gender'];
		$mobile=$_POST['mobile'];
		$username=$_POST['username'];
		$dept=$_POST['dept'];
		$notes=$_POST['notes'];
		$sql="insert into sys_staff (`name`,`gender`,`mobile`,`username`,`dept`,`notes`) values ('".$name."','".$gender."','".$mobile."','".$username."','".$dept."','".$notes."')";
		$Model = new Model() ;// 实例化一个model对象 没有对应任何数据表
        $data = $Model->execute($sql);
        if($data){
			echo "<script>window.parent.document.location=window.parent.document.location;window.parent.g_pop.close(); </script>";
        }else{
            echo "<script>window.parent.document.location=window.parent.document.location;window.parent.g_pop.close(); </script>";
        }

		/*$staff = M('sys_staff')->add($data);
		if(!empty($data)){
			$this->redirect('Staff/list_staff',0,10000,'数据添加成功!');
		}
		else{
			$this->error('数据写入错误');
		}*/
    }
	function list_user(){
		import("ORG.Util.Page");
		$cus=M('sys_user');
		$uname=$_REQUEST['uname'];
		$condtiton="";
		if ($uname == "")
		{
			$customers=$cus->field('name')->select();
		}
		if($uname != "")
		{
			$condtiton = "name like '%".$uname."%'";
			$customers=$cus->where($condtiton)->field('name')->select();
		}
		$count=$cus->where($condtiton)->count();
		$Page= new Page($count,5,$condtiton); // 实例化分页类传入总记录数和每页显示的记录数
    	$show= $Page->show(); // 分页显示输出
		$list = $cus->where($condtiton)->limit($Page->firstRow.','.$Page->listRows)->order('id')->select();
    	$this->assign('list',$list);
		$this->assign('uname',$uname);
		$this->assign('page',$show);
		$this->display();
	}
	function list_dept(){
		import("ORG.Util.Page");
		$dept=M('sys_dept');
		$deptname=$dept->field('name')->select();
		
		$count=$dept->count();
		$Page= new Page($count,10); // 实例化分页类传入总记录数和每页显示的记录数
    	$show= $Page->show(); // 分页显示输出
		$list = $dept->limit($Page->firstRow.','.$Page->listRows)->order('id')->select();
    	$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();

	}

    
	function moredelstaff(){
		$staffIds=$_REQUEST['staffIds'];
		if(isset($staffIds)){
			$sta=M('sys_staff')->delete($staffIds);
			if($sta>0){
				//$sta=M('sys_staff')->select();
				//echo $name=$sta['username'];
				//print_r($name);
				//die;
				//$sta=M('sys_staff')->delete($staffIds);
				 echo  "<script>alert('删除成功');history.back();</script>";
			}
			else{
				echo  "<script>alert('删除失败');history.back();</script>";
			}
		}
	}
    public function showonestaff() {
    	$id=$_GET["id"];
    	$staff = M('sys_staff');
		$data=$staff->getByid($id);
		$this->assign('data',$data);
		$this->display('showonestaff');
    }

    public function editonestaff() {
    	$id=$_GET["id"];
    	if(!empty($id)){
			$staff = M('sys_staff');
			$data=$staff->getByid($id);
			//var_dump($data);die;
			if(!empty($data)) {
				$this->assign('data',$data);
				$this->display('editonestaff');
			}else {
			    echo  "<script>alert('没有此用户信息');history.back();</script>";
			}
		}else {
			echo "<script>alert('编辑的内容不存在');history.back();</script>";
		}
    }

    public function saveeditstaff() {
    	$id = $_POST['id'];
    	$name=$_POST['name'];
		$gender=$_POST['gender'];
		$mobile=$_POST['mobile'];
		$username=$_POST['username'];
		$dept=$_POST['dept'];
		$notes=$_POST['notes'];
		$sql="update sys_staff set `name`='".$name."',`gender`='".$gender."',`mobile`='".$mobile."',`username`='".$username."',`dept`='".$dept."',`notes`='".$notes."' where id=".$id;
		$Model = new Model() ;// 实例化一个model对象 没有对应任何数据表
        $data = $Model->execute($sql);
        if($data){
			echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
        }
		else{
            echo "<script>window.parent.document.location.reload();window.parent.g_pop.close(); </script>";
		}
    }
	public function list_staff(){
		$this->redirect(__APP__.'/List/List/list2?param:table=sys_staff',array(),0,"");
	}
	//导入所有在职的员工
	public function importStaff(){
		$m = new Model();
		$orgid = System::orgid();
		$sql = "select id from sys_staff where SYS_ORGID = $orgid and statue = 1";
		$staffids = $m->query($sql);
		for($i = 0;$i < count($staffids);$i++){
			$staffid = $staffids[$i]['id'];
			$sql = "insert into oa_pm_staffcostsetting(staffid,SYS_ORGID)values($staffid,$orgid)";
			$m->execute($sql);
		}
	}

}
?>