<?php

namespace App\Core\Application\Service;

use App\Core\Domain\Entity\Transaction;
use App\SharedKernel\Application\Response;

class IssueTransactionResponse implements Response
{
    /** @var Transaction */
    private $transaction;


    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return string
     */
    public function getTransactionResource(): Transaction
    {
        return $this->transaction;
    }


}