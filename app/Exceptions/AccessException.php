<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/9/27
 * Time: 11:46
 */

namespace App\Exceptions;


class AccessException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}