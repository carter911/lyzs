<?php

return array (
  'autoload' => false,
  'hooks' => 
  array (
    'admin_login_init' => 
    array (
      0 => 'loginbg',
    ),
    'testhook' => 
    array (
      0 => 'onlineservice',
    ),
    'upload_config_init' => 
    array (
      0 => 'qiniu',
    ),
    'upload_after' => 
    array (
      0 => 'thumb',
    ),
  ),
  'route' => 
  array (
  ),
);