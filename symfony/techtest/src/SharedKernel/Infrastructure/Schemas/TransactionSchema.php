<?php

namespace App\SharedKernel\Infrastructure\Schemas;

use App\Core\Domain\Entity\Transaction;
use Neomerx\JsonApi\Schema\BaseSchema;

class TransactionSchema extends BaseSchema
{
    /**
     * Get resource type.
     *
     * @return string
     */
    public function getType(): string
    {
        return Transaction::class;
    }

    /**
     * Get resource identity. Newly created objects without ID may return `null` to exclude it from encoder output.
     *
     * @param Transaction $resource
     *
     * @return string|null
     */
    public function getId($resource): ?string
    {
        return $resource->getId();
    }

    /**
     * Get resource attributes.
     *
     * @param Transaction $resource
     *
     * @return iterable
     */
    public function getAttributes($resource): iterable
    {
        return [
            'issuerId' => $resource->getIssuerId(),
            'recieverId' => $resource->getRecieverId(),
            'transactionAmount' => $resource->getAmount()
        ];
    }

    /**
     * Get resource relationship descriptions.
     *
     * @param mixed $resource
     *
     * @return iterable
     */
    public function getRelationships($resource): iterable
    {
        return [];
    }
}