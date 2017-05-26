<?php
/**
 * ============================================================================
 *   * ADD BY YANG
 * ============================================================================
 * 广告控制器
 */
if(!defined('IN_WSTMALL')) {
	exit('Access Denied');
}
define('CHARSET', 'utf-8');
define('DBCHARSET', 'utf8');
define('TABLEPRE', 'wst_');

$env_items = array
(
	'os' => array(''),
	'php' => array(''),
	'attachmentupload' => array(),
	'gdversion' => array(),
	'diskspace' => array(),
);
$dir_items = array
(
  'Install' => array('path' => '/Install'),
  'Apps' => array('path' => '/Apps/Runtime'),
  'Upload' => array('path' => '/Upload'),
  'Conf' => array('path' => '/Apps/Common/Conf')
);
$func_items = array(
  'mysql_connect'=>array(),
  'file_get_contents'=>array(),
  'curl_init'=>array(),
  'mb_strlen'=>array()
);
?>