<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/8/13
 * Time: 17:38
 */

namespace App\Exceptions;


class PaymentException extends \Exception
{

    /**
     * PaymentException constructor.
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}