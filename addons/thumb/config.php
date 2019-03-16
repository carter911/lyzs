<?php

return array(
    0 =>
    array(
        'name'    => 'size',
        'title'   => '裁剪长宽',
        'type'    => 'number',
        'content' =>
        array(
        ),
        'value'   => '300',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '裁剪的最高长/宽',
        'ok'      => '',
        'extend'  => '',
    ),
    1 =>
    array(
        'name'    => 'replace',
        'title'   => '覆盖原图',
        'type'    => 'radio',
        'content' =>
        array(
            0 => '否',
            1 => '是',
        ),
        'value'   => '1',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '此项为是时，后缀不生效',
        'ok'      => '',
        'extend'  => '',
    ),
    2 =>
    array(
        'name'    => 'quality',
        'title'   => '图片质量',
        'type'    => 'number',
        'content' =>
        array(
        ),
        'value'   => '100',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '大于100小于10均按照100处理',
        'ok'      => '',
        'extend'  => '',
    ),
    3 =>
    array(
        'name'    => 'ext',
        'title'   => '文件后缀',
        'type'    => 'string',
        'content' =>
        array(
        ),
        'value'   => '-thumb',
        'rule'    => 'required',
        'msg'     => '',
        'tip'     => '最终效果a-thumb.jpg',
        'ok'      => '',
        'extend'  => '',
    ),
);
