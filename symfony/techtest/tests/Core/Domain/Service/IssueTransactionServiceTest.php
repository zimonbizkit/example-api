<?php

namespace Tests\Core\Domain\Service;

use App\Core\Domain\Entity\Balance;
use App\Core\Domain\Entity\Transaction;
use App\Core\Domain\Entity\User;
use App\Core\Domain\Exception\InsufficientFundsForTransactionException;
use App\Core\Domain\Exception\NegativeTransactionAmountException;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Core\Domain\Service\IssueTransactionService;
use App\SharedKernel\Domain\Service\IdentityProviderInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Tests\Core\Application\Service\IssueTransactionUseCaseTest;

class IssueTransactionServiceTest extends TestCase
{
    const FAKE_TRANSACTION_ID='f04b12ea-3c33-4975-900d-09e492673e5e';

    /** @var UserRepositoryInterface | ObjectProphecy */
    private $userRepositoryMock;

    /** @var IdentityProviderInterface | ObjectProphecy */
    private $identityProviderMock;

    /** @var IssueTransactionService */
    private $issueTransactionService;


    public function setUp(): void
    {
        $this->userRepositoryMock = $this->prophesize(UserRepositoryInterface::class);
        $this->identityProviderMock = $this->prophesize(IdentityProviderInterface::class);
        $this->issueTransactionService = new IssueTransactionService(
            $this->userRepositoryMock->reveal(),
            $this->identityProviderMock->reveal()
        );

    }

    public function testThatIfIssuerHasNotFundsThenAnExceptionWillBeThrown()
    {
        $issuer = new User(
            IssueTransactionUseCaseTest::FAKE_ISSUER_UUID,
            "John",
            Balance::register(10)
        );

        $reciever = new User(
            IssueTransactionUseCaseTest::FAKE_RECIEVER_UUID,
            "Sally",
            Balance::register(1)
        );

        $this->expectException(InsufficientFundsForTransactionException::class);

        $this->issueTransactionService->issue(
            $issuer,
            $reciever,
            20
        );
    }

    public function testThatIfTheAmountIsNegativeThenAnExceptionWillBeThrown()
    {
        $issuer = new User(
            IssueTransactionUseCaseTest::FAKE_ISSUER_UUID,
            "John",
            Balance::register(10)
        );

        $reciever = new User(
            IssueTransactionUseCaseTest::FAKE_RECIEVER_UUID,
            "Sally",
            Balance::register(1)
        );

        $this->expectException(NegativeTransactionAmountException::class);

        $this->issueTransactionService->issue(
            $issuer,
            $reciever,
            -20
        );
    }

    public function testThatIfTheAmountIsValidAndTheIssuerHasFundsThenThereWillBeATransaction()
    {
        $issuer = new User(
            IssueTransactionUseCaseTest::FAKE_ISSUER_UUID,
            "John",
            Balance::register(10)
        );

        $reciever = new User(
            IssueTransactionUseCaseTest::FAKE_RECIEVER_UUID,
            "Sally",
            Balance::register(1)
        );


        $this->identityProviderMock->provide()
            ->shouldBeCalledTimes(1)
            ->willReturn(self::FAKE_TRANSACTION_ID);

        $transaction = $this->issueTransactionService->issue(
            $issuer,
            $reciever,
            1
        );

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals(self::FAKE_TRANSACTION_ID, $transaction->getId());
        $this->assertEquals(IssueTransactionUseCaseTest::FAKE_ISSUER_UUID, $transaction->getIssuerId());
        $this->assertEquals(IssueTransactionUseCaseTest::FAKE_RECIEVER_UUID, $transaction->getRecieverId());
        $this->assertEquals(1, $transaction->getAmount());
    }

    public function tearDown(): void
    {
        unset(
            $this->userRepositoryMock,
            $this->identityProviderMock,
            $this->issueTransactionService
        );
    }
}
