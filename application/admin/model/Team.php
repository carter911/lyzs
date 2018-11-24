<?php

namespace app\admin\model;

use think\Model;

class Team extends Model
{
    // 表名
    protected $name = 'team';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'sex_text'
    ];
    

    
    public function getSexList()
    {
        return ['1' => __('Sex 1'),' 0' => __('Sex  0')];
    }     


    public function getSexTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['sex']) ? $data['sex'] : '');
        $list = $this->getSexList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
