<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/8/2
 * Time: 14:45
 */

namespace App\Exceptions;


class ParamException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}