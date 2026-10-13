<?php

namespace App\Exceptions;

use Exception;

class OrderNotFoundException extends Exception
{
    public function __construct(string $message = "الطلب المطلوب غير موجود!")
    {
        parent::__construct($message, 404);
    }

}
