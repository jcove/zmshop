<?php
return [
    'unique'               => ':attribute 已存在',
    'accepted'             => ':attribute 是被接受的',
    'active_url'           => ':attribute 必须是一个合法的 URL',
    'after'                => ':attribute 必须是 :date 之后的一个日期',
    'alpha'                => ':attribute 必须全部由字母字符构成。',
    'alpha_dash'           => ':attribute 必须全部由字母、数字、中划线或下划线字符构成',
    'alpha_num'            => ':attribute 必须全部由字母和数字构成',
    'array'                => ':attribute 必须是个数组',
    'before'               => ':attribute 必须是 :date 之前的一个日期',
    'between'              => [
        'numeric' => ':attribute 必须在 :min 到 :max 之间',
        'file'    => ':attribute 必须在 :min 到 :max KB之间',
        'string'  => ':attribute 必须在 :min 到 :max 个字符之间',
        'array'   => ':attribute 必须在 :min 到 :max 项之间',
    ],
    'boolean'              => ':attribute 字符必须是 true 或 false',
    'confirmed'            => ':attribute 二次确认不匹配',
    'date'                 => ':attribute 必须是一个合法的日期',
    'date_format'          => ':attribute 与给定的格式 :format 不符合',
    'different'            => ':attribute 必须不同于:other',
    'digits'               => ':attribute 必须是 :digits 位',
    'digits_between'       => ':attribute 必须在 :min and :max 位之间',
    'email'                => ':attribute必须是一个合法的电子邮件地址。',
    'filled'               => ':attribute是必填的',
    'exists'               => '选定的 :attribute 是无效的',
    'image'                => ':attribute 必须是一个图片 (jpeg, png, bmp 或者 gif)',
    'in'                   => '选定的 :attribute 是无效的',
    'integer'              => ':attribute 必须是个整数',
    'ip'                   => ':attribute 必须是一个合法的 IP 地址。',
    'max'                  => [
        'numeric' => ':attribute 的最大长度为 :max 位',
        'file'    => ':attribute 的最大为 :max',
        'string'  => ':attribute 的最大长度为 :max 字符',
        'array'   => ':attribute 的最大个数为 :max 个',
    ],
    'mimes'                => ':attribute 的文件类型必须是:values',
    'min'                  => [
        'numeric' => ':attribute 的最小长度为 :min 位',
        'string'  => ':attribute 的最小长度为 :min 字符',
        'file'    => ':attribute 大小至少为:min KB',
        'array'   => ':attribute 至少有 :min 项',
    ],
    'not_in'               => '选定的 :attribute 是无效的',
    'numeric'              => ':attribute 必须是数字',
    'regex'                => ':attribute 格式是无效的',
    'required'             => ':attribute必须填写',
    'required_if'          => ':attribute是必须的当 :other 是 :value',
    'required_with'        => ':attribute是必须的当 :values 是存在的',
    'required_with_all'    => ':attribute是必须的当 :values 是存在的',
    'required_without'     => ':attribute是必须的当 :values 是不存在的',
    'required_without_all' => ':attribute是必须的当 没有一个 :values 是存在的',
    'same'                 => ':attribute和:other必须匹配',
    'size'                 => [
        'numeric' => ':attribute 必须是 :size 位',
        'file'    => ':attribute 必须是 :size KB',
        'string'  => ':attribute 必须是 :size 个字符',
        'array'   => ':attribute 必须包括 :size 项',
    ],
    'url'                  => ':attribute 无效的格式',
    'timezone'             => ':attribute 必须个有效的时区',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes'           => [
        'username' => '用户名',
        'account'  => '账号',
        'captcha'  => '验证码',
        'mobile'   => '手机号',
        'password' => '密码',
        'content'  => '内容',
        'identity' => '手机号/用户名',
        'name'     =>   '名称',
        'model_id'  =>  '商品模型',
        'consignee' =>  '收货人',
        'country'   =>  '国家',
        'province'  =>  '省',
        'city'      =>  '城市/市',
        'district'  =>  '区',
        'address'   =>  '详细地址',
        'phone'     =>  '电话',
        'title'     =>  '标题',
        'old_password'=>'旧密码',
        'pay_code'      =>  '支付方式',
        'address_id'    =>  '收货地址',
        'country_id'            =>  '国家',
        'first_unit'        =>    '首重/件',
        'first_money'       =>    '运费',
        'continue_unit'     =>  '费用',
        'continue_money'    =>  '续重/件',
        'express_sn'        =>  '快递单号',
        'type'              =>  '类型',
        'express_code'      =>  '物流公司',
        'category_id'       =>  '分类',
        'store'             =>  '库存',
        'brand'             =>  '品牌',
        'freight_template_id'       =>  '运费模板',
        'description'       =>  '描述'
    ],
    'please_choose_payment' =>  '请选择支付方式',
    'please_choose_address' =>  '请选择收货地址'
];
