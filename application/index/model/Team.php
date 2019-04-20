<?php

namespace app\index\model;


use think\Db;
use think\Model;

class Team extends Model
{

    public function getTeamInfo($id)
    {
        Db::name('team')
            ->field('t.*,ts.name as style_name,td.name as team_door')
            ->alias('t')
            ->join('__team_style__ ts','t.team_style_id=ts.id')
            ->join('__team_door__ td','t.team_door_id=td.id')->find();
    }
}