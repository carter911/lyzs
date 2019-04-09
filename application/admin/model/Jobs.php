<?php

namespace app\admin\model;

use think\Model;

class Jobs extends Model
{
    // 表名
    protected $name = 'jobs';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'job_status_text'
    ];
    

    
    public function getJobStatusList()
    {
        return ['1' => __('Job_status 1'),'2' => __('Job_status 2')];
    }     


    public function getJobStatusTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['job_status']) ? $data['job_status'] : '');
        $list = $this->getJobStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
