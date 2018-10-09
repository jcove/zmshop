<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/18
 * Time: 10:33
 */

namespace App\Exceptions;


class OrderException extends \Exception
{
    public function __construct($message,$code)
    {
        parent::__construct($message,$code);
    }
}