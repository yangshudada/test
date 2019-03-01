<?php
return array(
    //'配置项'=>'配置值'
    //数据库配置信息
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'name', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '123456', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PARAMS' =>  array(), // 数据库连接参数
    'DB_PREFIX' => 't_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志

    'LOG_RECORD' => true, // 开启日志记录
    'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR', // 只记录EMERG ALERT CRIT ERR 错误
    'DEFAULT_MODULE' => 'Home', //默认模块
    'AUTH_CONFIG'=>array(
        'AUTH_ON'           => true,                      // 认证开关
        'AUTH_TYPE'         => 1,                         // 认证方式，1为实时认证；2为登录认证。
        'AUTH_GROUP'        => 't_company_authgroup',        // 用户组数据表名
        'AUTH_GROUP_ACCESS' => 't_company_authgroup_access', // 用户-用户组关系表
        'AUTH_RULE'         => 't_company_authrule',         // 权限规则表
        'AUTH_USER'         => 't_company_admin'             // 用户信息表
    ),
    //oss配置
    'ALIOSS_CONFIG'         => array(
        'OSS_ACCESS_ID'        => 'LTAIyuPSXpDFud8R',    // 阿里云oss key_id
        'OSS_ACCESS_KEY'    => '3tMZURoDlrMb2AzjoJwnRZxR4EYqbY',    // 阿里云oss key_secret
        'OSS_ENDPOINT'     => 'oss-cn-beijing.aliyuncs.com',    // 阿里云oss endpoint
        "OSS_TEST_BUCKET" => 'ceshiing',
        "OSS_WEB_SITE" =>'https://ceshiing.oss-cn-beijing.aliyuncs.com',
    ),
    //oss文件上传配置
    'oss_maxSize'=>1048576, //1M
    'oss_exts' =>array(// 设置附件上传类型
        'image/jpg',
        'image/gif',
        'image/png',
        'image/jpeg',
        'application/octet-stream',//阿里云好像都是通过二进制上传，似乎上面4个后缀设置起到什么用？
    ),
    'URL_ROUTER_ON'   => true, //开启路由
    'URL_MODEL' => '2' //url访问模式为rewrite模式
);