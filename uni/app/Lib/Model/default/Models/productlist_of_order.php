<?php return array (
  'title' => '订单产品列表',
  'tablename' => 'crm_orderproduct',
  'openurl' => '',
  'editurl' => 'CRM/Order/editproduct',
  'addurl' => 'CRM/Order/neworderproduct',
  'samewindow' => 'false',
  'filter' => '',
  'orderby' => 'producttype asc,version desc',
  'pagerows' => '',
  'hidepage' => 'false',
  'editintable' => 'true',
  'fields' => 
  array (
    0 => 
    array (
      'id' => 'producttype',
      'name' => '产品类别',
      'forsearch' => 'true',
    ),
    1 => 
    array (
      'id' => 'product',
      'name' => '产品名称',
      'forsearch' => 'true',
    ),
	2 => 
    array (
      'id' => 'netcount',
      'name' => '网版节点数',
      'forsearch' => 'true',
    ),
    3 => 
    array (
      'id' => 'version',
      'name' => '版本',
      'forsearch' => 'true',
    ),
    4 => 
    array (
      'id' => 'unit',
      'name' => '单位',
      'forsearch' => 'true',
    ),
    5 => 
    array (
      'id' => 'type',
      'name' => '购买类别',
      'forsearch' => 'true',
    ),
    6 => 
    array (
      'id' => 'amt',
      'name' => '数量',
      'forsearch' => 'true',
    ),
    
    7 => 
    array (
      'id' => 'price',
      'name' => '单价',
      'forsearch' => 'true',
    ),
    8 => 
    array (
      'id' => 'xishu',
      'name' => '价格系数',
      'forsearch' => 'true',
    ),
    9 => 
    array (
      'id' => 'totalamt',
      'name' => '金额',
      'forsearch' => 'true',
    ),
    10 => 
    array (
      'id' => 'dikou',
      'forsearch' => 'true',
      'name' => '抵扣金额',
    ),
    11 => 
    array (
      'id' => 'factmoney',
      'forsearch' => 'true',
      'name' => '成交价',
    ),
    12 => 
    array (
      'id' => 'suohao',
      'forsearch' => 'true',
      'name' => '锁号/序列号',
    ),
    13 => 
    array (
      'id' => 'modules',
      'forsearch' => 'true',
      'name' => '包含模块',
    ),
    14 => 
    array (
      'id' => 'addedmodules',
      'forsearch' => 'true',
      'name' => '附加模块',
    ),
    15 => 
    array (
      'id' => 'notes',
      'forsearch' => 'true',
      'name' => '备注',
    ),
  ),
) ?>