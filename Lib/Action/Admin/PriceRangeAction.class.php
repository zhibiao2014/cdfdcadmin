<?php
/**
*  前台价格区间
*/
class PriceRangeAction extends CommonAction {
  protected $db;
  function __construct() {
    parent::__construct();
    $this->db = D('PriceRange');
  }

  public function index() {
    if (isset( $_GET['type'] )) {
      $priceranges = $this->db->where( array( 'belong' => array( 'in' , array( 0, intval($_GET['type']) ) ) ) )->order("sort desc")->select();
    } else {
      $priceranges = $this->db->where( array( 'belong' => 2 ) )->order("sort desc")->select();
    }
    $this->assign('priceranges',$priceranges);
    $this->assign('types', array('3' => '商铺', '4' => '写字楼', '5' => '别墅','6' => '出租', '7' => '商铺出租', '8' => '写字楼出租', '9' => '别墅出租' ));
    $this->display();
  }

  public function add() {
    if (IS_POST) {
      $this->checkToken();
      $data = $_POST['info'];
      $data['price'] = implode(',', $data['price']);
      $data['siteid'] = $this->siteid;
      if ($id = $this->db->add($data)) {
        $this->success('添加成功', 'index');
      } else {
        $this->success('操作失败', 'index');
      }
    } else {
      $pid = isset( $_GET['pid'] ) ? intval($_GET['pid']) : 2;
      if ( !empty($pid) ) {
        $parent_pricerange = $this->db->find($pid);
        $this->assign( 'parent_pricerange', $parent_pricerange );
      }
      $this->assign( 'type', isset($parent_pricerange['belong']) ? $parent_pricerange['belong'] : 2 );
      $this->assign('types', array('3' => '商铺', '4' => '写字楼', '5' => '别墅','6' => '出租', '7' => '商铺出租', '8' => '写字楼出租', '9' => '别墅出租' ));
      $priceranges = $this->db->where( array( 'pid' => 2 ) )->select();
      $this->assign( 'pid', $pid );
      $this->assign( 'priceranges', $priceranges );
      $this->display();
    }
  }

  public function edit() {
    if (IS_POST) {
      $this->checkToken();
      $data = $_POST['info'];
      if ($this->db->where("id = %d",$_POST['id'])->save($data) !== false) {
        $this->success("更新成功！");
      } else {
        $this->error("更新失败! ");
      }
    } else {
      $pricerange = $this->db->find($_GET['id']);
      $pricerange['price'] = explode(',', $pricerange['price']);
      $this->assign('pricerange', $pricerange);
      $this->assign('types', array( '3' => '商铺', '4' => '写字楼', '5' => '别墅','6' => '出租', '7' => '商铺出租', '8' => '写字楼出租', '9' => '别墅出租' ));
      $this->display();
    }
  }

  public function listorder() {
    if (isset($_POST['listorders']) && is_array($_POST['listorders'])) {
      $sort = $_POST['listorders'];
      foreach ($sort as $k => $v) {
        $this->db->where(array('id'=>$k))->save(array('sort'=>$v));
      }
    }
    $this->success('排序成功');
  }

  public function delete() {
    if (isset($_POST['ids']) && is_array($_POST['ids'])) {
      if ($this->db->where(array('id' => array('in', $_POST['ids'])))->delete() !== false) {
        $this->success('删除成功！');
      } else {
        $this->error('删除失败！');
      }
    } else {
      if ($this->db->where(array('id' => $_GET['id']))->delete() !== false) {
        $this->success('删除成功');
      } else {
        $this->error('删除失败');
      }
    }
  }
}
?>