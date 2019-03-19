<?php

namespace App\Core\Domain\Service;

use App\Core\Domain\Entity\Balance;
use App\Core\Domain\Entity\Transaction;
use App\Core\Domain\Entity\User;
use App\Core\Domain\Exception\InsufficientFundsForTransactionException;
use App\Core\Domain\Exception\NegativeTransactionAmountException;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\SharedKernel\Domain\Service\IdentityProviderInterface;

class IssueTransactionService
{
    /** @var UserRepositoryInterface */
    private $userRepository;
    /**
     * @var IdentityProviderInterface
     */
    private $identityProvider;

    public function __construct(
        UserRepositoryInterface $userRepository,
        IdentityProviderInterface $identityProvider
    ) {
        $this->userRepository = $userRepository;
        $this->identityProvider = $identityProvider;
    }

    public function issue(User $issuer, User $reciever, float $amount): Transaction
    {
        $this->checkIfIssuerHasEnoughFunds($issuer, $amount);
        $this->checkIfAmountIsNotNegative($amount);

        $issuerNewBalance = Balance::register(
            $issuer->getBalance()->getValue() - $amount
        );

        $recieverNewBalance = Balance::register(
            $issuer->getBalance()->getValue() + $amount
        );

        $issuer->changeBalance($issuerNewBalance);
        $reciever->changeBalance($recieverNewBalance);

        $this->userRepository->save($issuer);
        $this->userRepository->save($reciever);

        $transaction = Transaction::register(
            $this->identityProvider->provide(),
            $issuer->getId(),
            $reciever->getId(),
            $amount
        );

        return $transaction;

    }

    private function checkIfIssuerHasEnoughFunds(User $issuer, float $amount)
    {
        if (($issuer->getBalance()->getValue() - $amount) < 0 ) {
            throw new InsufficientFundsForTransactionException(
                "User with id".$issuer->getId().
                "has insufficient amount on account for a transaction of ".$amount
            );
        }
    }

    private function checkIfAmountIsNotNegative($amount)
    {
        if ($amount <= 0) {
            throw new NegativeTransactionAmountException("Amount should be greater than zero");
        }
    }
}