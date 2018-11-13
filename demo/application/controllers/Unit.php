<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Unit extends CI_Controller {
  public function __construct() {
    parent::__construct();

    // 語系設定
    $this->language = $this->mod_config->getLanguage();
    $this->lang->load('general', $this->language);
  }
  
  /**
   * 測試語系
   */
  function test_language() {
    echo $this->lang->line('siteTitle');
  }

  /**
   * 測試GET/POST
   */
  function test_getpost() {
    // 估計需要的值
    $needsData = array('product_id', 'product_name', 'remark');
    // 必填欄位
    $requiredData = array('product_id', 'product_name'); 
    // 把結果丟到$data
    $data = $this->getpost->getpost_data($needsData, $requiredData, 'GET');
    if ($data) {
      // 正確
      $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'success', 'PROCESS_SUCCESS', $this->language);
    } else {
      // 回報缺少的內容
      $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'error', 'MISSING_DATA', $this->language);
      $json_arr['requred'] = $this->getpost->report_required($requiredData, 'GET');
    }
    echo json_encode($json_arr); // 把結果印出來測試看看
  }

  /**
   * 測試取得預設值
   */
  function get_setting() {
    echo $this->config->item('title');
  }

  /**
   * 測試資料庫
   */
  function get_table_data() {
    $data = $this->mongo_db->get('user_main');
    
    echo json_encode($data);
  }

  function create_user() {
    // PHP的陣列我們會用array()來表示
    $dataArray = array(
      // 裡面的物件用 key => value 來表示
      'user_name'=> 'Jone',
      'user_email'=> 'jone@gmail.com'
    );
    // 最後我們印出新增的內容
    echo $this->mongo_db->insert('user_main', $dataArray);
  }
}
