<?php

namespace App\Core\Infrastructure\Ui\Controllers;

use App\Core\Application\Service\GetUserBalanceRequest;
use App\Core\Application\Service\GetUserBalanceUseCase;
use App\Core\Domain\Entity\Balance;
use App\SharedKernel\Application\Exception\ResourceNotFoundException;
use App\SharedKernel\Infrastructure\Schemas\BalanceSchema;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Neomerx\JsonApi\Encoder\Encoder;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;

class BalancesController
{
    /** @var GetUserBalanceUseCase */
    private $getUserBalanceUseCase;

    public function __construct(GetUserBalanceUseCase $getUserBalanceUseCase)
    {
        $this->getUserBalanceUseCase = $getUserBalanceUseCase;
    }

    /**
     * @Operation(
     *     tags={"GET User Balance"},
     *     consumes={"application/vnd.api+json"},
     *     produces={"application/vnd.api+json"},
     *     @SWG\Parameter(
     *          name="userId",
     *          in="path",
     *          type="string",
     *          description="The user Id",
     *          required=true
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Returns a newly created balance object from user",
     *         @Model(type="App\Core\Domain\Entity\Balance")
     *     ),
     *     @SWG\Response(
     *          response=400,
     *          description="Request is malformed"
     *     ),
     *     @SWG\Response(
     *          response=404,
     *          description="User is not found"
     *     ),
     *     @SWG\Response(
     *          response=500,
     *          description="An error has been found"
     *     )
     * )
     */
    public function get(string $userId)
    {
        try {
            $response = $this->getUserBalanceUseCase
                ->handle(
                    new GetUserBalanceRequest($userId)
                );

            return new Response((Encoder::instance([
                Balance::class => BalanceSchema::class,
            ])
                ->withEncodeOptions(JSON_PRETTY_PRINT)
            )->encodeData(
                $response->getBalanceResource()

            ), Response::HTTP_OK);
        }catch (ResourceNotFoundException $e) {
            return new Response(
                json_encode($e->getMessage()),
                Response::HTTP_NOT_FOUND
            );
        }
    }
}