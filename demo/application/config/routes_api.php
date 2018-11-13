<?php 
  $config['routes_api'] = array(
    'user'=> array(
      'get'=> array(
        'get_user'=> 'get_user'
      ),
      'post'=> array()
    )
  );

  $config['routes_table'] = array(
    'user'=> array(
      'table'=> 'user_main',
      'method'=> array(
        'insert'
      ),
      'detals'=> array(
        "insert"=> array(
          // 負責驗證
          'verify'=> array(),
          // 傳輸方式
          'type'=> 'post',
          // 帶入的參數
          'query'=> array(
            'user_uid'=> 'user_uid', 
            'user_displayName'=> 'user_displayName', 
            'user_email'=> 'user_email', 
            'user_photoURL'=> 'user_photoURL'
          ),
          // 相似的關鍵字
          'likes'=> array(),
          // 特殊的方法
          'record'=> array(),
          // 預設內容
          'default'=> array(
            'updateAt'=> 'code_date',
            'updateAtTime'=> 'code_time',
            'tsUpdateAt'=> 'code_timestamp',
            'lastLogin'=> '',
            'lastLoginTime'=> '',
            'tsLastLogin'=> '0'
          ),
          // 必填資料
          'require'=> array(
            'user_uid',
            'user_displayName',
            'user_email',
            'user_photoURL'
          )
        ),
      )
    )
  );
?>