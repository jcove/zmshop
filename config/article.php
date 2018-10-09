<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/20
 * Time: 11:02
 */
return [
    'route'                         =>  [
        'prefix'                    =>  'article',
        'auth_prefix'               =>  'article',
        'middleware'                =>  [
            'auth'                      =>  'auth:api',
            'normal'                    =>  'common_data'
        ]
    ]
];