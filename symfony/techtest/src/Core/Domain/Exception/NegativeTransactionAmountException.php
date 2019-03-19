<?php

namespace App\Core\Domain\Exception;

use App\SharedKernel\Domain\Exception\DomainException;

class NegativeTransactionAmountException extends DomainException
{
}