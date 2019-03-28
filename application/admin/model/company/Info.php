<?php

namespace app\admin\model\company;

use think\Model;

class Info extends Model
{
    // 表名
    protected $name = 'company_info';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'top_state_text'
    ];
    

    
    public function getTopStateList()
    {
        return ['1' => __('Top_state 1'),'0' => __('Top_state 0')];
    }     


    public function getTopStateTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['top_state']) ? $data['top_state'] : '');
        $list = $this->getTopStateList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
