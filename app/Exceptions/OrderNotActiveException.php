<?php

namespace App\Exceptions;

use Exception;

class OrderNotActiveException extends Exception
{
    public function __construct(string $message = "لا يمكن إجراء هذه العملية لأن الطلب غير فعال أو مكتمل!")
    {
        parent::__construct($message, 409);
    }
}
