<?php

return array (
  'app_key' => 
  array (
    'name' => 'app_key',
    'title' => 'app_key',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => 'KrGHoCcpnajTWIwWPSksomPWa02MfqDw5iotrwfC',
    'rule' => 'required',
    'msg' => '',
    'tip' => '请在个人中心 > 密钥管理中获取 > AK',
    'ok' => '',
    'extend' => '',
  ),
  'secret_key' => 
  array (
    'name' => 'secret_key',
    'title' => 'secret_key',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => 'jkTsrcAEyIvH3pdT6cwHCVCSU2clGTSIzjolSF7C',
    'rule' => 'required',
    'msg' => '',
    'tip' => '请在个人中心 > 密钥管理中获取 > SK',
    'ok' => '',
    'extend' => '',
  ),
  'bucket' => 
  array (
    'name' => 'bucket',
    'title' => 'bucket',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => 'lyzs',
    'rule' => 'required',
    'msg' => '',
    'tip' => '存储空间名称',
    'ok' => '',
    'extend' => '',
  ),
  'uploadurl' => 
  array (
    'name' => 'uploadurl',
    'title' => '上传接口地址',
    'type' => 'select',
    'content' => 
    array (
      'https://upload-z0.qiniup.com' => '华东 https://upload-z0.qiniup.com',
      'https://upload-z1.qiniup.com' => '华北 https://upload-z1.qiniup.com',
      'https://upload-z2.qiniup.com' => '华南 https://upload-z2.qiniup.com',
      'https://upload-na0.qiniup.com' => '北美 https://upload-na0.qiniup.com',
      'https://upload-as0.qiniup.com' => '东南亚 https://upload-as0.qiniup.com',
    ),
    'value' => 'https://upload-z0.qiniup.com',
    'rule' => 'required',
    'msg' => '',
    'tip' => '推荐选择最近的地址',
    'ok' => '',
    'extend' => '',
  ),
  'cdnurl' => 
  array (
    'name' => 'cdnurl',
    'title' => 'CDN地址',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => 'http://cdn.ly-home.cn/',
    'rule' => 'required',
    'msg' => '',
    'tip' => '未绑定CDN的话可使用七牛分配的测试域名',
    'ok' => '',
    'extend' => '',
  ),
  'notifyenabled' => 
  array (
    'name' => 'notifyenabled',
    'title' => '启用服务端回调',
    'type' => 'bool',
    'content' => 
    array (
    ),
    'value' => '0',
    'rule' => '',
    'msg' => '',
    'tip' => '本地开发请禁用服务端回调',
    'ok' => '',
    'extend' => '',
  ),
  'notifyurl' => 
  array (
    'name' => 'notifyurl',
    'title' => '回调通知地址',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => 'http://ly.e-shigong.com/addons/qiniu/index/notify',
    'rule' => '',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  'savekey' => 
  array (
    'name' => 'savekey',
    'title' => '保存文件名',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => '/uploads/$(year)$(mon)$(day)/$(etag)$(ext)',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  'expire' => 
  array (
    'name' => 'expire',
    'title' => '上传有效时长',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => '600',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  'maxsize' => 
  array (
    'name' => 'maxsize',
    'title' => '最大可上传',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => '10M',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  'mimetype' => 
  array (
    'name' => 'mimetype',
    'title' => '可上传后缀格式',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => 'jpg,png,bmp,jpeg,gif,zip,rar,xls,xlsx',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  'multiple' => 
  array (
    'name' => 'multiple',
    'title' => '多文件上传',
    'type' => 'bool',
    'content' => 
    array (
    ),
    'value' => '1',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
);
