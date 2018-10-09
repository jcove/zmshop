<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/8/11
 * Time: 15:24
 */

namespace App\Exceptions;


class SmsException extends \Exception
{

    /**
     * SmsException constructor.
     */
    public function __construct($message,$code)
    {
        parent::__construct($message,$code);
    }
}