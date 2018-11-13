<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class User extends CI_Model {
  public function __construct() {
    parent::__construct();
    // 語系設定
    $this->language = $this->mod_config->getLanguage();
  }


  function get_user($getpostData) {
    // 估計需要的值
    $needsData = array('product_id', 'product_name', 'remark');
    // 必填欄位
    $requiredData = array('product_id', 'product_name');
    // 把結果丟到$data
    $data = $this->getpost->getpost_array_for_api($getpostData, $needsData, $requiredData);
    if ($data) {
      // 正確
      $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'success', 'PROCESS_SUCCESS', $this->language);
    } else {
      // 回報缺少的內容
      $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'error', 'MISSING_DATA', $this->language);
      $json_arr['requred'] = $this->getpost->report_required_for_api($getpostData, $requiredData);
    }
    // 最後回傳資料
    return $json_arr;
  }
}

?>