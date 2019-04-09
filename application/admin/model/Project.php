<?php

namespace app\admin\model;

use think\Model;

class Project extends Model
{
    // 表名
    protected $name = 'project';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'is_show_text'
    ];
    

    
    public function getIsShowList()
    {
        return ['1' => __('Is_show 1'),'2' => __('Is_show 2')];
    }     


    public function getIsShowTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['is_show']) ? $data['is_show'] : '');
        $list = $this->getIsShowList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
