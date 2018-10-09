<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/8/11
 * Time: 11:32
 */

namespace App\Exceptions;


class UserException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}