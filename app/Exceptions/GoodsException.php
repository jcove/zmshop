<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/7/11
 * Time: 11:34
 */

namespace App\Exceptions;




use Exception;

class GoodsException extends Exception
{

    /**
     * GoodsException constructor.
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}