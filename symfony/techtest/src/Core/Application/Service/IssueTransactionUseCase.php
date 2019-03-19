<?php

namespace App\Core\Application\Service;

use App\Core\Domain\Exception\UserNotFoundException;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Core\Domain\Service\IssueTransactionService;
use App\SharedKernel\Application\Exception\LogicException;
use App\SharedKernel\Application\Request;
use App\SharedKernel\Application\Response;
use App\SharedKernel\Application\UseCaseInterface;
use App\SharedKernel\Domain\Exception\DomainException;

class IssueTransactionUseCase implements UseCaseInterface
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var IssueTransactionService */
    private $issueTransactionService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        IssueTransactionService $issueTransactionService
    ) {
        $this->userRepository = $userRepository;
        $this->issueTransactionService = $issueTransactionService;
    }

    /**
     * @param IssueTransactionRequest | Request $request
     * @return IssueTransactionResponse | Response
     * @throws UserNotFoundException
     */
    public function handle(Request $request): Response
    {
        try {
            list($issuer, $recipient) = $this->getIssuerAndReciever($request);

            $transaction = $this->issueTransactionService->issue(
                $issuer,
                $recipient,
                $request->getAmount()
            );

            return new IssueTransactionResponse($transaction);
        } catch (DomainException $e) {
            throw new LogicException($e);
        } catch (UserNotFoundException $e) {
            throw new LogicException($e);
        }
    }

    /**
     * @param Request $request
     * @return array
     * @throws UserNotFoundException
     */
    private function getIssuerAndReciever(Request $request): array
    {
        $issuer = $this->userRepository->findById($request->getIssuerId());
        if (null === $issuer) {
            throw new UserNotFoundException(
                "Transaction issuer with id " .
                $request->getIssuerId() .
                " has not been found"
            );
        }

        $recipient = $this->userRepository->findById($request->getRecipientId());
        if (null === $recipient) {
            throw new UserNotFoundException(
                "Transaction reciever with id" .
                $request->getRecipientId() .
                "has not been found"
            );
        }

        return [$issuer, $recipient];
    }
}