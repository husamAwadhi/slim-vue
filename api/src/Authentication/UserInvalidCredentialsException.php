<?php

namespace App\Authentication;

use App\Domain\DomainException\DomainException;

class UserInvalidCredentialsException extends DomainException
{
    public $message = 'Credentials does not match';

    public int $statusCode = 401;
}
