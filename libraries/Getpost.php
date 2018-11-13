<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  v1.0 過濾必填與非必填功能
 **/

class Getpost {
  /**
   * 專門給API使用，不需要透過CI URL
   * 主要是針對透過授權進入API頁面
   * 事先會把資料抓取出來，所以只需要去做比較即可。
   */
	function getpost_array_for_api($getpostData = array(), $need = array(), $requred = array()){
		$res = array();
		foreach ($need as $key => $value) {
      // 檢查是否在需要名單內
			if (in_array($value, $requred) && !isset($getpostData[$value])){
				return false;
			} else {
        // 檢查是否有檔案的參數
        if (substr($value, 0, 5) == 'FILE_') {
            $res['tmp_'.substr($value, strlen($value) - 5)] = $getpostData[$value];
        } else {
            // 一般的參數
            if (isset($getpostData[$value])) {
                $res[$value] = $getpostData[$value];
            } else {
                $res[$value] = '';
            }
        }
			}
		}
		return $res;
  }

  /**
   * 檢查必填項目，沒有被填寫到的資料就會被丟回陣列回傳
   */
  function report_required_for_api($getpostData, $requred) {
    $res = array(); // 結果都會儲存到內部

		foreach ($requred as $key => $value) {
			if(!isset($getpostData[$value])){
				$res[] = $value;
			}
    }

    return $res;
  }

  /**
   * 將需要的Get/Post資料轉換成一個陣列並回傳
   * data = 列出所有要取得的資料
   * required = 必填項目 (預設空白)
   * type = 傳輸方式(預設GET) e.g. POST
   */
  function getpost_data($data, $required = array(), $type = 'GET') {
		$CI =& get_instance(); // 載入CI元件
		$CI->load->helper('url'); // 載入URL元件
    $res = array(); // 結果都會儲存到內部
    
    foreach ($data as $key => $value) {
      $item = '';
      if ($type === 'GET') $item = $CI->input->get($value);
      if ($type === 'POST') $item = $CI->input->post($value);
      // 確認是否有在必填名單
      if (in_array($value, $required) && $item == '') {
        // 檢查後沒有對應的資料
        return false;
      }
      $res[$value] = $item;
    } // --- End ForEach
    return $res;
  }

  /**
   * 檢查必填項目，沒有被填寫到的資料就會被丟回陣列回傳
   */
  function report_required($requred, $type = 'GET') {
		$CI =& get_instance(); // 載入CI元件
		$CI->load->helper('url'); // 載入URL元件
    $res = array(); // 結果都會儲存到內部

		foreach ($requred as $key => $value) {
      $item = '';
      if ($type === 'GET') $item = $CI->input->get($value);
      if ($type === 'POST') $item = $CI->input->post($value);
			if($item == ""){
				$res[] = $value;
			}
    }

    return $res;
  }
}


/* End of file Getpost.php */
/* Location: ./application/libraries/Getpost.php */