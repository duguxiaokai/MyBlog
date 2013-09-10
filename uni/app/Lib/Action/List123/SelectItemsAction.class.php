<?php 
//这个类放在这里不合适，需要移走

class SelectItemsAction extends CommonAction{
    function selectitems() {
        $no=$_GET['no'];
        $num = M('crm_order')->where("no='".$no."'and status='确认'")->count();
        if($num>0){
            echo "<script>document.write('不能操作已确认的订单');</script>";die;
        }else{
            $this->display('selectitems');
        }
    }

    function showmitems() {
        $pro = M('crm_product');
        $data = $pro->field('name,no,type,price,modules')->order('type,orderindex')->select();
        echo json_encode($data);
    }
	function showbuy(){
		$pro = M('sys_item');
        $data = $pro->field('name')->where("typename='购买类别'")->order("orderlist asc")->select();
        echo json_encode($data);
	}
	function showedition(){
		$pro = M('sys_item');
        $data = $pro->field('name')->where("typename='产品版本'")->order("orderlist desc")->select();
        echo json_encode($data);
	}

}
?>