<?php

namespace App\SharedKernel\Domain\ValueObject;

class EntityId
{
    /** @var string */
    private $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromString(string $id)
    {
        return new self($id);
    }

    public function getValue(): string
    {
        return $this->id;
    }

}