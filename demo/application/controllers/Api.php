<?php if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

class Api extends CI_Controller {
  public function __construct() {
    parent::__construct();
    // 語系設定
    $this->language = $this->mod_config->getLanguage();
  }

  function authenticate($path_name, $func_name) {
    // 驗證設定檔是否有符合規則
    $auth = $this->check_api_file($path_name, $func_name);
    // 確認回傳是可用的API
    if ($auth['sys_code'] == 200) {
      // 載入API Model
      $this->load->model('API/'.ucfirst($auth['path']), $auth['path']);
      // 如果發現是圖片或是檔案，也把他丟進來
      $get_post_image_data = array();
      $get_post_image_data = array_merge($get_post_image_data, $this->get_post_data($auth['type']));
      $get_post_image_data = array_merge($get_post_image_data, $_FILES);
      // 接著使用此API並把結果回傳輸出出去
      $response = $this->$auth['path']->$auth['func']($get_post_image_data);
      header('Content-type:text/json');
      echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
  }

  /**
   * 加入方法為可以在API自訂資料夾與名稱的Controller，
   * 主要是針對API去分開操作，至於路徑設定檔案，
   * 可以到Config資料夾內的routes_api.php進行新增。
   */
  function check_api_file($path_name, $func_name) {
    // 取得API清單
    $routes = $this->config->item('routes_api');
    // 確認是否有指定的API路徑與資料
    if (isset($routes[$path_name]) && (isset($routes[$path_name]['get'][$func_name]) || isset($routes[$path_name]['post'][$func_name]))) {
      if (isset($routes[$path_name]['get'][$func_name])) {
        // 有此API可以進行接下來動作
        $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'success', 'GET_DATA_SUCCESS', $this->language);
        $json_arr['path'] = $path_name;
        $json_arr['func'] = $routes[$path_name]['get'][$func_name]; // 取得名稱
        $json_arr['type'] = 'get'; // 傳輸方法
      } else if (isset($routes[$path_name]['post'][$func_name])) {
        // 有此API可以進行接下來動作
        $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'success', 'GET_DATA_SUCCESS', $this->language);
        $json_arr['path'] = $path_name;
        $json_arr['func'] = $routes[$path_name]['post'][$func_name]; // 取得名稱
        $json_arr['type'] = 'post'; // 傳輸方法
      } else {
        $res = $this->check_api_table($path_name, $func_name);
        if ($res) {
          // 取得名單上的API
          $rt = $this->config->item('routes_table');
          $json_arr = $this->to_work($path_name, $func_name, $this->get_post_data($rt[$path_name]['detals'][$func_name]['type']));
        } else {
          // 找不到API，回報錯誤
          $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'error', 'DATA_FAIL', $this->language);
        }
      }
    } else {
      // 找不到API，回報錯誤
      $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'error', 'DATA_FAIL', $this->language);
    }
    return $json_arr; // 回傳結果
  }

  // 確認是否在routers_table名單上
  function check_api_table($path_name, $func_name) {
    $methods = $this->config->item('routes_table');
    if (isset($methods[$path_name])) {
      $work = false;
      foreach ($methods[$path_name]['method'] as $key => $value) {
          if ($value == $func_name) $work = $value;
      }
      return $work;
    } else {
      return false;
    }
  }

  // 取得傳輸進來的資料
  function get_post_data($type) {
    if ($type == 'get') {
      return $this->input->get();
    } else if ($type == 'post') {
      return $this->input->post();
    } else {
      return array();
    }
  }

  // 進行日常預設工作
  function to_work($path_name, $func_name, $data) {
    if ($func_name == 'exist') {
      return $this->to_work_exist($path_name, $func_name, $data);
    } else if ($func_name == 'get_list') {
      return $this->to_work_get_list($path_name, $func_name, $data);
    } else if ($func_name == 'get_once') {
      return $this->to_work_get_once($path_name, $func_name, $data);
    } else if ($func_name == 'insert') {
      return $this->to_work_insert($path_name, $func_name, $data);
    } else if ($func_name == 'update') {
      return $this->to_work_update($path_name, $func_name, $data);
    } else if ($func_name == 'delete') {
      return $this->to_work_delete($path_name, $func_name, $data);
    }
  }

  // 檢查名稱是否存在
  function to_work_exist($path_name, $func_name, $data) {
    // 取得table內容
    $methods = $this->config->item('routes_table');
    $table = $methods[$path_name]['table'];
    $info = $methods[$path_name]['detals'][$func_name];

    // 驗證需要的元素
    $required = array();
    foreach ($info['require'] as $keyRequired => $valueRequired) {
      if (!isset($data[$valueRequired])) array_push($required, $valueRequired);
    }
    // 如果缺少資源跳出
    if (sizeof($required) > 0) {
      $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'error', 'MISSING_DATA', $this->language);
      $json_arr['requred'] = $required;
      return $json_arr;
    }

    // 負責驗證的元素
    $verify = array();
    foreach ($info['verify'] as $key => $value) {
      if (isset($data[$value])) {
        // 驗證是否為編號
        if ($key == '_id') {
            $verify[$key] = new MongoId($data[$value]);
        } else {
            $verify[$key] = $data[$value];
        }
      }
    }

    // 加入邏輯佇列
    $dataQuery['verify'] = $verify;
    $dataQuery['table'] = $table;
    $response = $this->mod_universal->chk_once($dataQuery);
    $json_arr['exist'] = $response;
    $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'success', 'GET_DATA_SUCCESS', $this->language);
    return $json_arr;
  }
  
  // 取得特定多筆資料
  function to_work_get_list($path_name, $func_name, $data) {
    $methods = $this->config->item('routes_table');
    $table = $methods[$path_name]['table'];
    $info = $methods[$path_name]['detals'][$func_name];

    // 驗證需要的元素
    $required = array();
    foreach ($info['require'] as $keyRequired => $valueRequired) {
      if (!isset($data[$valueRequired])) array_push($required, $valueRequired);
    }
    // 如果缺少資源跳出
    if (sizeof($required) > 0) {
      $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'error', 'DATA_FAIL', $this->language);
      $json_arr['requred'] = $required;
      return $json_arr;
    }

    // 負責驗證的元素
    $verify = array();
    foreach ($info['verify'] as $key => $value) {
      if (isset($data[$value])) {
        if ($key == '_id') {
          $verify[$key] = new MongoId($data[$value]);
        } else {
          $verify[$key] = $data[$value];
        }
      }
    }

    // 負責寫入關鍵字元素內容
    $likes = array();
    foreach ($info['likes'] as $key => $value) {
      if (isset($data[$key])) $likes[$key] = $data[$key];
    }

    // 負責寫入限制元素
    $record = array();
    foreach ($info['record'] as $key => $value) {
      if (isset($data[$key])) $record[$key] = $data[$key];
    }

    // 確認資料數量 預設抓取 30 筆資料
    if (!isset($record['limit'])) {
      $record['limit'] = 30;
    }

    // 確認頁數
    if (!isset($record['page'])) {
      $record['page'] = 1;
    }

    // 加入邏輯佇列
    $dataQuery['verify'] = $verify;
    $dataQuery['likes'] = $likes;
    $dataQuery['record'] = $record;
    $dataQuery['table'] = $table;
    $response = $this->mod_universal->get_list($dataQuery);
    $json_arr['response'] = $response;
    $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'success', 'GET_DATA_SUCCESS', $this->language);
    return $json_arr;
  }
  // 取得特定單筆資料
  function to_work_get_once($path_name, $func_name, $data) {
    $methods = $this->config->item('routes_table');
    $table = $methods[$path_name]['table'];
    $info = $methods[$path_name]['detals'][$func_name];

    // 驗證需要的元素
    $required = array();
    foreach ($info['require'] as $keyRequired => $valueRequired) {
      if (!isset($data[$valueRequired])) array_push($required, $valueRequired);
    }
    // 如果缺少資源跳出
    if (sizeof($required) > 0) {
      $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'error', 'DATA_FAIL', $this->language);
      $json_arr['requred'] = $required;
      return $json_arr;
    }

    // 負責驗證的元素
    $verify = array();
    foreach ($info['verify'] as $key => $value) {
      if (isset($data[$value])) {
        if ($key == '_id') {
          $verify[$key] = new MongoId($data[$value]);
        } else {
          $verify[$key] = $data[$value];
        }
      }
    }

    // 負責寫入關鍵字元素內容
    $likes = array();
    foreach ($info['likes'] as $key => $value) {
      if (isset($data[$key])) $likes[$key] = $data[$key];
    }

    // 負責寫入限制元素
    $record = array();
    foreach ($info['record'] as $key => $value) {
      if (isset($data[$key])) $record[$key] = $data[$key];
    }

    // 確認資料數量 預設抓取 30 筆資料
    if (!isset($record['limit'])) {
      $record['limit'] = 30;
    }

    // 確認頁數
    if (!isset($record['page'])) {
      $record['page'] = 1;
    }

    // 加入邏輯佇列
    $dataQuery['verify'] = $verify;
    $dataQuery['likes'] = $likes;
    $dataQuery['record'] = $record;
    $dataQuery['table'] = $table;
    $response = $this->mod_universal->get_once($dataQuery);
    $json_arr['response'] = $response;
    $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'success', 'GET_DATA_SUCCESS', $this->language);
    return $json_arr;
  }
  // 新增資料
  function to_work_insert($path_name, $func_name, $data) {
    $methods = $this->config->item('routes_table');
    $table = $methods[$path_name]['table'];
    $info = $methods[$path_name]['detals'][$func_name];

    // 驗證需要的元素
    $required = array();
    foreach ($info['require'] as $keyRequired => $valueRequired) {
      if (!isset($data[$valueRequired])) array_push($required, $valueRequired);
    }
    // 如果缺少資源跳出
    if (sizeof($required) > 0) {
      $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'error', 'MISSING_DATA', $this->language);
      $json_arr['requred'] = $required;
      return $json_arr;
    }

    // 負責寫入元素內容
    $query = array();
    foreach ($info['query'] as $key => $value) {
      if (isset($data[$key])) $query[$key] = $data[$key];
    }

    // 加入預設條件
    $query = array_merge($query, $this->set_default_data($info['default']));

    // 加入邏輯佇列
    $dataQuery['data'] = $query;
    $dataQuery['table'] = $table;
    $response = $this->mod_universal->insert($dataQuery);
    $json_arr['response'] = $response;
    $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'success', 'GET_DATA_SUCCESS', $this->language);
    return $json_arr;
  }
  // 修改資料
  function to_work_update($path_name, $func_name, $data) {
    $methods = $this->config->item('routes_table');
    $table = $methods[$path_name]['table'];
    $info = $methods[$path_name]['detals'][$func_name];

    // 驗證需要的元素
    $required = array();
    foreach ($info['require'] as $keyRequired => $valueRequired) {
      if (!isset($data[$valueRequired])) array_push($required, $valueRequired);
    }
    // 如果缺少資源跳出
    if (sizeof($required) > 0) {
      $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'error', 'MISSING_DATA', $this->language);
      $json_arr['requred'] = $required;
      return $json_arr;
    }

    // 負責驗證的元素
    $verify = array();
    foreach ($info['verify'] as $key => $value) {
      if (isset($data[$value])) {
        if ($key == '_id') {
          $verify[$key] = new MongoId($data[$value]);
        } else {
          $verify[$key] = $data[$value];
        }
      }
    }

    // 負責寫入元素內容
    $query = array();
    foreach ($info['query'] as $key => $value) {
      if (isset($data[$key])) $query[$key] = $data[$key];
    }

    // 加入預設條件
    $query = array_merge($query, $this->set_default_data($info['default']));

    // 加入邏輯佇列
    $dataQuery['data'] = $query;
    $dataQuery['table'] = $table;
    $response = $this->mod_universal->update($dataQuery);
    $json_arr['response'] = $response;
    $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'success', 'GET_DATA_SUCCESS', $this->language);
    return $json_arr;
  }
  // 刪除資料
  function to_work_delete($path_name, $func_name, $data) {
    $methods = $this->config->item('routes_table');
    $table = $methods[$path_name]['table'];
    $info = $methods[$path_name]['detals'][$func_name];

    // 驗證需要的元素
    $required = array();
    foreach ($info['require'] as $keyRequired => $valueRequired) {
      if (!isset($data[$valueRequired])) array_push($required, $valueRequired);
    }
    // 如果缺少資源跳出
    if (sizeof($required) > 0) {
      $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'error', 'MISSING_DATA', $this->language);
      $json_arr['requred'] = $required;
      return $json_arr;
    }

    // 負責驗證的元素
    $verify = array();
    foreach ($info['verify'] as $key => $value) {
      if (isset($data[$value])) {
        if ($key == '_id') {
          $verify[$key] = new MongoId($data[$value]);
        } else {
          $verify[$key] = $data[$value];
        }
      }
    }

    // 加入邏輯佇列
    $dataQuery['data'] = $query;
    $dataQuery['table'] = $table;
    $response = $this->mod_universal->delete($dataQuery);
    $json_arr['response'] = $response;
    $json_arr = $this->mod_config->msgResponse((isset($json_arr))?$json_arr:array(), 'success', 'GET_DATA_SUCCESS', $this->language);
    return $json_arr;
  }

}

?>