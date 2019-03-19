<?php

namespace App\Core\Domain\Entity;

use App\SharedKernel\Domain\Entity;

class User implements Entity
{
    /** @var string */
    private $id;

    /**  @var string */
    private $name;

    /** @var Balance */
    private $balance;

    public function __construct(string $id, string $name, Balance $balance)
    {
        $this->id = $id;
        $this->name = $name;
        $this->balance = $balance;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBalance(): Balance
    {
        return $this->balance;
    }

    public function changeBalance(Balance $balance)
    {
        $this->balance = $balance;
    }

}