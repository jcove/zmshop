<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/13
 * Time: 14:06
 */

namespace App\Exceptions;


class CartException extends \Exception
{

    /**
     * CartException constructor.
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}