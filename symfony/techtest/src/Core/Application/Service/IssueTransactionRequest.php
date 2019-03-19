<?php

namespace App\Core\Application\Service;

use App\SharedKernel\Application\Request;

class IssueTransactionRequest implements Request
{
    /** @var string */
    private $issuerId;

    /** @var string */
    private $recipientId;

    /** @var float */
    private $amount;

    public function __construct(string $issuerId, string $recipientId, float $amount)
    {
        $this->issuerId = $issuerId;
        $this->recipientId = $recipientId;
        $this->amount = $amount;
    }

    public function getIssuerId(): string
    {
        return $this->issuerId;
    }

    public function getRecipientId(): string
    {
        return $this->recipientId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }


}