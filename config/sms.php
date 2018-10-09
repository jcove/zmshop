<?php
return [
    //id
    'ACCESS_KEY_ID'=>env('ACCESS_KEY_ID'),
    //秘钥
    'ACCESS_KEY_SECRET'=>env('ACCESS_KEY_SECRET'),
    //短信签名
    'signName'=>env('signName'),
    //短信模板编号
    'templateCode'=>env('templateCode'),
    //短信模板中的字段
    'field'=>'number',
    //短信模板默认内容
    'content'=>rand(1000, 9999),
    //短信模板中可选参数
    'product'=> 'dsd',
    '5c'            =>  [
        'encode'                => 'UTF-8',  //页面编码和短信内容编码为GBK。重要说明：如提交短信后收到乱码，请将GBK改为UTF-8测试。如本程序页面为编码格式为：ASCII/GB2312/GBK则该处为GBK。如本页面编码为UTF-8或需要支持繁体，阿拉伯文等Unicode，请将此处写为：UTF-8

        'username'              =>  'whwj',

        'password'              =>  'asdf12345',

        'api_key'               =>  '8dfac869737beaf47f3d1eec439a427c',

        'sign'                  =>  '威海无界'
    ]
];
