<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/8/29
 * Time: 10:55
 */

namespace App\Exceptions;


class RefundGoodsException extends ValidateException
{
    public function __construct($message, $code)
    {
        parent::__construct($message,$code);
    }
}