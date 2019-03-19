<?php

namespace App\Core\Domain\Entity;

class Balance
{
    /** @var float */
    private $balance;

    public function __construct(float $balance)
    {
        $this->balance = $balance;
    }

    public static function register(float $balance)
    {
        return new self($balance);
    }

    public function getValue(): float
    {
        return $this->balance;
    }
}