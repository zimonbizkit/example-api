<?php

namespace App\SharedKernel\Infrastructure\Schemas;

use App\Core\Domain\Entity\Balance;
use Neomerx\JsonApi\Schema\BaseSchema;

class BalanceSchema extends BaseSchema
{

    /**
     * Get resource type.
     *
     * @return string
     */
    public function getType(): string
    {
        return Balance::class;
    }

    /**
     * Get resource identity. Newly created objects without ID may return `null` to exclude it from encoder output.
     *
     * @param Balance $resource
     *
     * @return string|null
     */
    public function getId($resource): ?string
    {
        return null;
    }

    /**
     * Get resource attributes.
     *
     * @param Balance $resource
     *
     * @return iterable
     */
    public function getAttributes($resource): iterable
    {
        return [
            'value' => $resource->getValue()
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