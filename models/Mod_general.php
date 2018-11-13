<?php defined('BASEPATH') or exit('No direct script access allowed');

class Mod_universal extends CI_Model {
  public function __construct() {
      parent::__construct();
  }

  /**
   * 確認資料是否存在
   * verify = 驗證
   * likes = 相似關鍵字
   * table = 資料庫名稱 (必要)
   */
  function chk_once($dataQuery){
    if (isset($dataQuery['verify'])) $this->mongo_db->where($dataQuery['verify']);
    if (isset($dataQuery['likes'])) {
      foreach ($dataQuery['likes'] as $key => $value) {
          $this->mongo_db->like($key, $value);
      }
    }
    $response = $this->mongo_db->count($dataQuery['table']);
    if ($response == 0) {
      return false;
    } else {
      return true;
    }
  }

  /**
   * 取得資料數量
   * verify = 驗證
   * likes = 相似關鍵字
   * table = 資料庫名稱 (必要)
   * record = 特殊資料
   */
  function get_count($dataQuery){
    if (isset($dataQuery['verify'])) $this->mongo_db->where($dataQuery['verify']);
    if (isset($dataQuery['likes'])) {
      foreach ($dataQuery['likes'] as $key => $value) {
        $this->mongo_db->like($key, $value);
      }
    }
    if (isset($dataQuery['record']['between'])) {
      $this->mongo_db->where_between($dataQuery['record']['between']['item'], 
      $dataQuery['record']['between']['start'], $dataQuery['record']['between']['end']);
    }
    if (isset($dataQuery['record']['where_in'])) {
      $this->mongo_db->where_in($dataQuery['record']['where_in']['key'], $dataQuery['record']['where_in']['value']);
    }
    if (isset($dataQuery['record']['or_where'])) {
      $this->mongo_db->where_or($dataQuery['record']['or_where']);
    }
    if (isset($dataQuery['record']['where_gte'])) {
      $this->mongo_db->where_gte($dataQuery['record']['where_gte']['key'], $dataQuery['record']['where_gte']['value']);
    }
    if (isset($dataQuery['record']['where_lte'])) {
      $this->mongo_db->where_lte($dataQuery['record']['where_lte']['key'], $dataQuery['record']['where_lte']['value']);
    }
    if (isset($dataQuery['record']['order_by'])) {
      $this->mongo_db->order_by($dataQuery['record']['order_by']);
    } else {
      $this->mongo_db->order_by(array('createAt'=> 'DESC', 'createAtTime'=> 'DESC'));
    }
    if (isset($dataQuery['record']['where_not_in'])) {
      $this->mongo_db->where_not_in($dataQuery['record']['where_not_in']['key'], $dataQuery['record']['where_not_in']['value']);
    }
    $response = $this->mongo_db->count($dataQuery['table']);
    return $response;
  }

  /**
   * 取得單一資料
   * verify = 驗證
   * likes = 相似關鍵字
   * table = 資料庫名稱 (必要)
   * record = 特殊資料
   */
  function get_once($dataQuery){
    if (isset($dataQuery['verify'])) $this->mongo_db->where($dataQuery['verify']);
    if (isset($dataQuery['likes'])) {
      foreach ($dataQuery['likes'] as $key => $value) {
        $this->mongo_db->like($key, $value);
      }
    }
    if (isset($dataQuery['record']['between'])) {
      $this->mongo_db->where_between($dataQuery['record']['between']['item'], 
      $dataQuery['record']['between']['start'], $dataQuery['record']['between']['end']);
    }
    if (isset($dataQuery['record']['where_in'])) {
      $this->mongo_db->where_in($dataQuery['record']['where_in']['key'], $dataQuery['record']['where_in']['value']);
    }
    if (isset($dataQuery['record']['or_where'])) {
      $this->mongo_db->where_or($dataQuery['record']['or_where']);
    }
    if (isset($dataQuery['record']['where_gte'])) {
      $this->mongo_db->where_gte($dataQuery['record']['where_gte']['key'], $dataQuery['record']['where_gte']['value']);
    }
    if (isset($dataQuery['record']['where_lte'])) {
      $this->mongo_db->where_lte($dataQuery['record']['where_lte']['key'], $dataQuery['record']['where_lte']['value']);
    }
    if (isset($dataQuery['record']['order_by'])) {
      $this->mongo_db->order_by($dataQuery['record']['order_by']);
    } else {
      $this->mongo_db->order_by(array('createAt'=> 'DESC', 'createAtTime'=> 'DESC'));
    }
    if (isset($dataQuery['record']['where_not_in'])) {
      $this->mongo_db->where_not_in($dataQuery['record']['where_not_in']['key'], $dataQuery['record']['where_not_in']['value']);
    }
    $response = $this->mongo_db->find_one($dataQuery['table']);
    return $response;
  }

  /**
   * 取得特定資料
   * verify = 驗證
   * likes = 相似關鍵字
   * table = 資料庫名稱 (必要)
   * record = 特殊資料
   */
  function get_list($dataQuery){
    if (isset($dataQuery['verify'])) $this->mongo_db->where($dataQuery['verify']);
    if (isset($dataQuery['likes'])) {
      foreach ($dataQuery['likes'] as $key => $value) {
        $this->mongo_db->like($key, $value);
      }
    }
    if (isset($dataQuery['record']['limit'])) $this->mongo_db->limit($dataQuery['record']['limit']);
    if (isset($dataQuery['record']['page'])) $this->mongo_db->offset($dataQuery['record']['limit']*($dataQuery['record']['page'] - 1));
    if (isset($dataQuery['record']['between'])) {
      $this->mongo_db->where_between($dataQuery['record']['between']['item'], 
      $dataQuery['record']['between']['start'], $dataQuery['record']['between']['end']);
    }
    if (isset($dataQuery['record']['where_in'])) {
      $this->mongo_db->where_in($dataQuery['record']['where_in']['key'], $dataQuery['record']['where_in']['value']);
    }
    if (isset($dataQuery['record']['or_where'])) {
      $this->mongo_db->where_or($dataQuery['record']['or_where']);
    }
    if (isset($dataQuery['record']['where_gte'])) {
      $this->mongo_db->where_gte($dataQuery['record']['where_gte']['key'], $dataQuery['record']['where_gte']['value']);
    }
    if (isset($dataQuery['record']['where_lte'])) {
      $this->mongo_db->where_lte($dataQuery['record']['where_lte']['key'], $dataQuery['record']['where_lte']['value']);
    }
    if (isset($dataQuery['record']['order_by'])) {
      $this->mongo_db->order_by($dataQuery['record']['order_by']);
    } else {
      $this->mongo_db->order_by(array('createAt'=> 'DESC', 'createAtTime'=> 'DESC'));
    }
    if (isset($dataQuery['record']['where_not_in'])) {
      $this->mongo_db->where_not_in($dataQuery['record']['where_not_in']['key'], $dataQuery['record']['where_not_in']['value']);
    }
    $response = $this->mongo_db->get($dataQuery['table']);
    return $response;
  }

  /**
   * 取得所有特定資料
   * verify = 驗證
   * likes = 相似關鍵字
   * table = 資料庫名稱 (必要)
   * record = 特殊資料
   */
  function get_all($dataQuery){
    if (isset($dataQuery['verify'])) $this->mongo_db->where($dataQuery['verify']);
    if (isset($dataQuery['likes'])) {
      foreach ($dataQuery['likes'] as $key => $value) {
        $this->mongo_db->like($key, $value);
      }
    }
    if (isset($dataQuery['record']['between'])) {
      $this->mongo_db->where_between($dataQuery['record']['between']['item'], 
      $dataQuery['record']['between']['start'], $dataQuery['record']['between']['end']);
    }
    if (isset($dataQuery['record']['where_in'])) {
      $this->mongo_db->where_in($dataQuery['record']['where_in']['key'], $dataQuery['record']['where_in']['value']);
    }
    if (isset($dataQuery['record']['or_where'])) {
      $this->mongo_db->where_or($dataQuery['record']['or_where']);
    }
    if (isset($dataQuery['record']['where_gte'])) {
      $this->mongo_db->where_gte($dataQuery['record']['where_gte']['key'], $dataQuery['record']['where_gte']['value']);
    }
    if (isset($dataQuery['record']['where_lte'])) {
      $this->mongo_db->where_lte($dataQuery['record']['where_lte']['key'], $dataQuery['record']['where_lte']['value']);
    }
    if (isset($dataQuery['record']['order_by'])) {
      $this->mongo_db->order_by($dataQuery['record']['order_by']);
    } else {
      $this->mongo_db->order_by(array('createAt'=> 'DESC', 'createAtTime'=> 'DESC'));
    }
    if (isset($dataQuery['record']['where_not_in'])) {
      $this->mongo_db->where_not_in($dataQuery['record']['where_not_in']['key'], $dataQuery['record']['where_not_in']['value']);
    }
    $response = $this->mongo_db->get($dataQuery['table']);
    return $response;
  }

  /**
   * 新增資料
   * data = 傳入的資料內容
   * table = 資料庫名稱 (必要)
   */
  function insert($dataQuery) {
    $dataQuery['data']['createAt'] = date('Y-m-d');
    $dataQuery['data']['createAtTime'] = date('H:i:s');
    $dataQuery['data']['tsCreateAt'] = time();
    $dataQuery['data']['updateAt'] = date('Y-m-d');
    $dataQuery['data']['updateAtTime'] = date('H:i:s');
    $dataQuery['data']['tsUpdateAt'] = time();
    $response = $this->mongo_db->insert($dataQuery['table'], $dataQuery['data']);
    return $response;
  }

  /**
   * 更新資料
   * verify = 驗證
   * likes = 相似關鍵字
   * data = 傳入的資料內容
   * table = 資料庫名稱 (必要)
   * record = 特殊資料
   */
  function update($dataQuery){
    if (isset($dataQuery['verify'])) $this->mongo_db->where($dataQuery['verify']);
    if (isset($dataQuery['likes'])) {
      foreach ($dataQuery['likes'] as $key => $value) {
        $this->mongo_db->like($key, $value);
      }
    }
    if (isset($dataQuery['record']['between'])) {
      $this->mongo_db->where_between($dataQuery['record']['between']['item'], 
      $dataQuery['record']['between']['start'], $dataQuery['record']['between']['end']);
    }
    if (isset($dataQuery['record']['where_in'])) {
      $this->mongo_db->where_in($dataQuery['record']['where_in']['key'], $dataQuery['record']['where_in']['value']);
    }
    if (isset($dataQuery['record']['or_where'])) {
      $this->mongo_db->where_or($dataQuery['record']['or_where']);
    }
    if (isset($dataQuery['record']['where_gte'])) {
      $this->mongo_db->where_gte($dataQuery['record']['where_gte']['key'], $dataQuery['record']['where_gte']['value']);
    }
    if (isset($dataQuery['record']['where_lte'])) {
      $this->mongo_db->where_lte($dataQuery['record']['where_lte']['key'], $dataQuery['record']['where_lte']['value']);
    }
    if (isset($dataQuery['record']['where_not_in'])) {
      $this->mongo_db->where_not_in($dataQuery['record']['where_not_in']['key'], $dataQuery['record']['where_not_in']['value']);
    }
    if (isset($dataQuery['record']['push'])) {
      $this->mongo_db->push($dataQuery['record']['push']['key'], $dataQuery['record']['push']['value']);
    }
    if (isset($dataQuery['record']['pull'])) {
      $this->mongo_db->pull($dataQuery['record']['pull']['key'], $dataQuery['record']['pull']['value']);
    }
    $this->mongo_db->set($dataQuery['data']);
    $response = $this->mongo_db->update_all($dataQuery['table']);
    return $response;
  }

  /**
   * 刪除指定資料
   * verify = 寫入的條件式
   * table = 資料庫名稱
   */
  function delete($dataQuery) {
    if (isset($dataQuery['verify'])) $this->mongo_db->where($dataQuery['verify']);
    if (isset($dataQuery['likes'])) {
      foreach ($dataQuery['likes'] as $key => $value) {
        $this->mongo_db->like($key, $value);
      }
    }
    if (isset($dataQuery['record']['between'])) {
      $this->mongo_db->where_between($dataQuery['record']['between']['item'], 
      $dataQuery['record']['between']['start'], $dataQuery['record']['between']['end']);
    }
    if (isset($dataQuery['record']['where_in'])) {
      $this->mongo_db->where_in($dataQuery['record']['where_in']['key'], $dataQuery['record']['where_in']['value']);
    }
    if (isset($dataQuery['record']['or_where'])) {
      $this->mongo_db->where_or($dataQuery['record']['or_where']);
    }
    if (isset($dataQuery['record']['where_gte'])) {
      $this->mongo_db->where_gte($dataQuery['record']['where_gte']['key'], $dataQuery['record']['where_gte']['value']);
    }
    if (isset($dataQuery['record']['where_lte'])) {
      $this->mongo_db->where_lte($dataQuery['record']['where_lte']['key'], $dataQuery['record']['where_lte']['value']);
    }
    if (isset($dataQuery['record']['where_not_in'])) {
      $this->mongo_db->where_not_in($dataQuery['record']['where_not_in']['key'], $dataQuery['record']['where_not_in']['value']);
    }
    $response = $this->mongo_db->delete($dataQuery['table']);
    return $response;
  }

  /**
   * 刪除全部資料
   * verify = 寫入的條件式
   * table = 資料庫名稱
   */
  function delete_all($dataQuery) {
    if (isset($dataQuery['verify'])) $this->mongo_db->where($dataQuery['verify']);
    if (isset($dataQuery['likes'])) {
      foreach ($dataQuery['likes'] as $key => $value) {
        $this->mongo_db->like($key, $value);
      }
    }
    if (isset($dataQuery['record']['between'])) {
      $this->mongo_db->where_between($dataQuery['record']['between']['item'], 
      $dataQuery['record']['between']['start'], $dataQuery['record']['between']['end']);
    }
    if (isset($dataQuery['record']['where_in'])) {
      $this->mongo_db->where_in($dataQuery['record']['where_in']['key'], $dataQuery['record']['where_in']['value']);
    }
    if (isset($dataQuery['record']['or_where'])) {
      $this->mongo_db->where_or($dataQuery['record']['or_where']);
    }
    if (isset($dataQuery['record']['where_gte'])) {
      $this->mongo_db->where_gte($dataQuery['record']['where_gte']['key'], $dataQuery['record']['where_gte']['value']);
    }
    if (isset($dataQuery['record']['where_lte'])) {
      $this->mongo_db->where_lte($dataQuery['record']['where_lte']['key'], $dataQuery['record']['where_lte']['value']);
    }
    if (isset($dataQuery['record']['where_not_in'])) {
      $this->mongo_db->where_not_in($dataQuery['record']['where_not_in']['key'], $dataQuery['record']['where_not_in']['value']);
    }
    $response = $this->mongo_db->delete_all($dataQuery['table']);
    return $response;
  }

  /**
   * 取得特定欄位.
   * verify = 寫入的條件式
   * table = 資料庫名稱
   */
  function display_word($word, $where, $table) {
    $this->mongo_db->where($where);
    $res = $this->mongo_db->find_one($table);
    return $res[$word];
  }
}

/* End of file Mod_universal.php */