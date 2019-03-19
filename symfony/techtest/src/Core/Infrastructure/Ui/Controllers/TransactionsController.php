<?php

namespace App\Core\Infrastructure\Ui\Controllers;

use App\Core\Application\Service\IssueTransactionRequest;
use App\Core\Application\Service\IssueTransactionResponse;
use App\Core\Application\Service\IssueTransactionUseCase;
use App\Core\Domain\Entity\Transaction;
use App\SharedKernel\Application\Exception\LogicException;
use App\SharedKernel\Domain\Exception\DomainException;
use App\SharedKernel\Infrastructure\Schemas\TransactionSchema;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Neomerx\JsonApi\Encoder\Encoder;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionsController
{
    /**
     * @var IssueTransactionUseCase
     */
    private $issueTransactionUseCase;

    public function __construct(IssueTransactionUseCase $issueTransactionUseCase)
    {
        $this->issueTransactionUseCase = $issueTransactionUseCase;
    }
    /**
     * @Operation(
     *     tags={"POST User Transaction"},
     *     consumes={"application/vnd.api+json"},
     *     produces={"application/vnd.api+json"},
     *     @SWG\Parameter(
     *          name="data",
     *          in="body",
     *          type="json",
     *          description="Transaction data",
     *          required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="recipient", type="string"),
     *              @SWG\Property(property="amount", type="number")
     *          )
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Returns a newly created transaction",
     *         @Model(type="App\Core\Domain\Entity\Transaction")
     *     ),
     *     @SWG\Response(
     *          response=400,
     *          description="Request is malformed"
     *     ),
     *     @SWG\Response(
     *          response=500,
     *          description="An error has been found"
     *     )
     * )
     */
    public function post(string $userId, Request $request)
    {
        try {
            $body = json_decode($request->getContent())->data;

            /** @var IssueTransactionResponse $response */
            $response = $this->issueTransactionUseCase->handle(
                new IssueTransactionRequest(
                    $userId,
                    $body->recipient,
                    $body->amount
                )
            );

            return new Response(
                (Encoder::instance([
                    Transaction::class => TransactionSchema::class,
                ])
                    ->withEncodeOptions(JSON_PRETTY_PRINT)
                )->encodeData(
                    $response->getTransactionResource()

                ),
                Response::HTTP_CREATED
            );
        }catch (LogicException $e) {
            return new Response(
                json_encode(['error'=> $e->getMessage()],JSON_PRETTY_PRINT),
                Response::HTTP_BAD_REQUEST
            );
        }catch (\Exception $e) {
            return new Response(
                json_encode(['error'=> $e->getMessage()],JSON_PRETTY_PRINT),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}