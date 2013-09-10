<?php
class TeamAction extends CommonAction{
    //显示所有任务信息
    public function oa_teamevaluate_list() {
        $this->redirect('../List/List/list2?param:table=oa_teamevaluate_list',array(),0,"");
    }

}

?>