<?php
return array(
    //'配置项'=>'配置值'
    //设置模板替换变量
    'TMPL_PARSE_STRING' => array(
        '__CSS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Css',
        '__IMAGE__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Image',
        '__JS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Js',
        '__THREEPART__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Three_part',
    ),
    'LANG_SWITCH_ON' => true,   // 开启语言包功能
    'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
    'DEFAULT_LANG' => 'zh-tw', // 默认语言
    'LANG_LIST' => 'zh-tw', // 允许切换的语言列表
    'VAR_LANGUAGE' => 'l', // 默认语言切换变量
);