<?php
 return array(
		'URL_MODEL' => 0,
	    'URL_CASE_INSENSITIVE'  =>  false,
	    'VAR_PAGE'=>'p',
	    'PAGE_SIZE'=>15,
		'DB_TYPE'=>'mysql',
	    'DB_HOST'=>'localhost',
	    'DB_NAME'=>'integral',
	    'DB_USER'=>'root',
	    'DB_PWD'=>'root',
	    'DB_PREFIX'=>'inte_',
	    'DEFAULT_C_LAYER' =>  'Action',
	    'DEFAULT_CITY' => '440100',
	    'DATA_CACHE_SUBDIR'=>true,
        'DATA_PATH_LEVEL'=>2, 
	    'SESSION_PREFIX' => 'FYSMALL', //'WSTMALL',
        'COOKIE_PREFIX'  => 'FYSMALL', //'WSTMALL',
		'LOAD_EXT_CONFIG' => 'wst_config',

        'isDevelop'=>true   // 正式环境请修改为false
	);
?>