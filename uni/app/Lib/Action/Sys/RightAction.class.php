<?php
class RightAction extends CommonAction{

    public function list_right() {
    	$fun = M('sys_function');
    	$str1 = "";
		$groupname=$fun->where("groups='".$str1."'")->field('name')->select();
		//var_dump($groupname);die;
		$num = count($groupname);
		for($i=0;$i<$num;$i++){
			$fun_name[$i] = $fun->where("groups='".$groupname[$i]['name']."'")->field('name')->select();
			//array_unshift($fun_name[$i],$groupname[$i]['name']);
			//var_dump($fun_name[1]);
			foreach($fun_name[$i] as $v){
				array_unshift($v,$groupname[$i]['name']);
				//var_dump($v);
			}
		}



		//die;
    	$right=M('sys_right');
    	$name=M('sys_staff');
    	$data=$right->select();
    	$functions = $right->field('functions')->select();
    	$a = $this->changearray($functions);
    	$this->assign('data',$data);
    	$this->assign('function',$a);
    	$this->display('list_right');


    }

    function list_function() {


    }
    function changearray($functions) {

    	//$num = count($functions);

    	foreach($functions as $v){
    		$str = $v['functions'];
    		$functions_arr = explode(';',$str);
    		$num = count($functions_arr);
    		for($i=0;$i<$num;$i++){
    			$functions_arrs = explode(':',$functions_arr[$i]);
    			$functions_arrss = explode(',',$functions_arrs[1]);
    			array_unshift($functions_arrss,$functions_arrs[0]);
    			$a[$i]=$functions_arrss;
    		}
    		//var_dump($a);
    	}
    	return $a;
    }


    public function checkuser(){
    	$pro = $_REQUEST['aa'];
    	//将参数解码
   		$str = urldecode($pro);
   		$user = M('sys_right');
   		$rs = $user->where("user like '%".$str."%'")->field('user')->select();
   		//var_dump($rs);die;
   		$str='';
			foreach($rs as $v ){
				$str.='<tr><td>'.$v['user'].'</td></tr>';
			}
    	echo $str;
    }


}
?>