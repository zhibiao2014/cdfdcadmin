<?php
/**
*  二手房出售公共数据表Model
*/
class BuyShopModel extends UserCenterCommonModel {

  protected $tableName = 'i_wanna_buy_property';

  public function lists( $where=array(), $order = "id desc" ) {
    $houses = $this->where( array_merge( array('siteid' => get_siteid() ), $where ) )->order($order)->page((isset($_GET['p']) ? $_GET['p'] : 0).',20')->select();
    import("ORG.Util.Page");
    $count = $this->where( array_merge( array('siteid' => get_siteid() ), $where ) )->count();
    $Page       = new Page($count,20);
    $show       = $Page->show();
    return array("data" => $houses, "page" => $show);
  }

}