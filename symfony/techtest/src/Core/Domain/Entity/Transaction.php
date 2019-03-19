<?php

namespace App\Core\Domain\Entity;

use App\SharedKernel\Domain\Entity;

class Transaction implements Entity
{
    /** @var string */
    private $issuer;

    /** @var string */
    private $reciever
    ;
    /** @var float */
    private $amount;

    /** @var string */
    private $id;

    private function __construct(string $id, string $issuerId, string $recieverId, float $amount)
    {
        $this->id = $id;
        $this->issuer = $issuerId;
        $this->reciever = $recieverId;
        $this->amount = $amount;
    }

    public static function register(string $id, string $issuerId, string $recieverId, float $amount)
    {
        return new self($id, $issuerId, $recieverId, $amount);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getIssuerId(): string
    {
        return $this->issuer;
    }

    public function getRecieverId(): string
    {
        return $this->reciever;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}