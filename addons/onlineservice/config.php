<?php

return array (
  0 => 
  array (
    'name' => 'port',
    'title' => '通讯端口',
    'type' => 'number',
    'content' => 
    array (
    ),
    'value' => '8586',
    'rule' => 'required',
    'msg' => '',
    'tip' => '通讯端口,默认8585',
    'ok' => '',
    'extend' => '',
  ),
  1 => 
  array (
    'name' => 'register_port',
    'title' => '注册服务端口',
    'type' => 'number',
    'content' => 
    array (
    ),
    'value' => '1328',
    'rule' => 'required',
    'msg' => '',
    'tip' => '注册服务端口,默认1328',
    'ok' => '',
    'extend' => '',
  ),
  2 => 
  array (
    'name' => 'secret_key',
    'title' => '通讯密钥',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => 'XCYGD5R3L64JUZ',
    'rule' => 'required',
    'msg' => '',
    'tip' => '通讯密钥请自行修改(14位随机字符串)',
    'ok' => '',
    'extend' => '',
  ),
  3 => 
  array (
    'name' => 'service_title',
    'title' => '在线客服标题',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => '在线客服',
    'rule' => 'required',
    'msg' => '',
    'tip' => '尽量不要太长',
    'ok' => '',
    'extend' => '',
  ),
  4 => 
  array (
    'name' => 'service_logo',
    'title' => '在线客服Logo',
    'type' => 'image',
    'content' => 
    array (
    ),
    'value' => '/assets/addons/onlineservice/img/service_logo_default.jpg',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  5 => 
  array (
    'name' => 'mimetype',
    'title' => '上传文件后缀',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => 'jpg,png,bmp,jpeg,gif,zip,rar,xls,xlsx,doc,docx',
    'rule' => 'required',
    'msg' => '',
    'tip' => '尽量不要修改',
    'ok' => '',
    'extend' => '',
  ),
  6 => 
  array (
    'name' => 'maxsize',
    'title' => '上传文件大小',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => '10mb',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  7 => 
  array (
    'name' => 'speak_time',
    'title' => '发言的间隔',
    'type' => 'number',
    'content' => 
    array (
    ),
    'value' => '0',
    'rule' => 'required',
    'msg' => '',
    'tip' => '设置为0，则不受限制,设置完需要重启服务',
    'ok' => '',
    'extend' => '',
  ),
  8 => 
  array (
    'name' => 'speak_msg',
    'title' => '发言太快提醒',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => '你说话太快了,喝杯咖啡休息一下吧!',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  9 => 
  array (
    'name' => 'wx_service_logo',
    'title' => '插件作者微信',
    'type' => 'image',
    'content' => 
    array (
    ),
    'value' => '/assets/addons/onlineservice/img/weixin_service.jpg',
    'rule' => 'required',
    'msg' => '',
    'tip' => '插件作者微信,欢迎反馈,提建议!',
    'ok' => '',
    'extend' => '',
  ),
);
