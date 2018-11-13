<?php 

// 主要放置所有的成功碼與預設回應，未來可以自動加載到各個編輯器。

  /**
   * @apiDefine sucCodeExample
   * @apiSuccessExample {json} Response-Example:
   * {
   *      "sys_code": "回應編號",
   *      "sys_num": "成功編號",
   *      "sys_msg": "成功訊息"
   * }
   */

  /**
   * @apiDefine sucCode_20001
   * @apiSuccess PROCESS_SUCCESS 20001 處理成功
   */
  $config['suc_code']['PROCESS_SUCCESS'] = array(
      "sys_code"=> "200",
      "sys_num"=> "20001",
      "sys_msg"=> array(
          "zh-tw"=> '處理成功',
          "en-us"=> 'Process Success'
      )
  );

  /**
   * @apiDefine sucCode_20002
   * @apiSuccess GET_DATA_SUCCESS 20002 取得資料成功
   */
  $config['suc_code']['GET_DATA_SUCCESS'] = array(
      "sys_code"=> "200",
      "sys_num"=> "20002",
      "sys_msg"=> array(
          "zh-tw"=> '取得資料成功',
          "en-us"=> 'Get data Success'
      )
  );

?>
