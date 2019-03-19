<?php

namespace Tests\Core\Application\Service;

use App\Core\Application\Service\IssueTransactionRequest;
use App\Core\Application\Service\IssueTransactionResponse;
use App\Core\Application\Service\IssueTransactionUseCase;
use App\Core\Domain\Entity\Balance;
use App\Core\Domain\Entity\Transaction;
use App\Core\Domain\Entity\User;
use App\Core\Domain\Exception\UserNotFoundException;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Core\Domain\Service\IssueTransactionService;
use App\SharedKernel\Application\Exception\LogicException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class IssueTransactionUseCaseTest extends TestCase
{
    const FAKE_ISSUER_UUID='7620d09e-ba1a-45d6-a01d-b0b43c7e031c';
    const FAKE_RECIEVER_UUID='9cc5f688-f19a-48b9-aa95-086b6997d6d8';
    const FAKE_TRANSACTION_ID='8a25d6af-d7a2-4b41-8d49-dd0adf643b55';
    const FAKE_TRANSACTION_AMOUNT=100;
    /** @var UserRepositoryInterface | ObjectProphecy */
    private $userRepositoryMock;

    /** @var IssueTransactionService | ObjectProphecy */
    private $issueTransactionServiceMock;

    /** @var IssueTransactionUseCase */
    private $issueTransactionUseCase;


    public function setUp(): void
    {

        $this->userRepositoryMock = $this->prophesize(UserRepositoryInterface::class);
        $this->issueTransactionServiceMock = $this->prophesize(IssueTransactionService::class);
        $this->issueTransactionUseCase = new IssueTransactionUseCase(
            $this->userRepositoryMock->reveal(),
            $this->issueTransactionServiceMock->reveal()
        );
    }

    public function tearDown(): void
    {
        unset(
            $this->userRepositoryMock,
            $this->issueTransactionServiceMock,
            $this->issueTransactionUseCase
        );
    }

    public function testThatWithNoIssuerTheresAnExceptionThrown()
    {
        $this->userRepositoryMock->findById(
            self::FAKE_ISSUER_UUID

        )->shouldBeCalledTimes(1)
            ->willThrow(
                UserNotFoundException::class
            );

        $this->expectException(LogicException::class);

        $this->issueTransactionUseCase->handle(
            new IssueTransactionRequest(
                self::FAKE_ISSUER_UUID,
                self::FAKE_RECIEVER_UUID,
                self::FAKE_TRANSACTION_AMOUNT
            )
        );
    }

    public function testThatWithNoRecieverTheresAnExceptionThrown()
    {
        $fakeUser = new User(
            self::FAKE_ISSUER_UUID,
            'john doe',
            Balance::register(1000)
        );

        $this->userRepositoryMock->findById(self::FAKE_ISSUER_UUID)
            ->shouldBeCalledTimes(1)
            ->willReturn($fakeUser);

        $this->userRepositoryMock->findById(self::FAKE_RECIEVER_UUID)
            ->shouldBeCalledTimes(1)
            ->willThrow(
                UserNotFoundException::class
            );

        $this->expectException(LogicException::class);

       $this->issueTransactionUseCase->handle(
           new IssueTransactionRequest(
               self::FAKE_ISSUER_UUID,
               self::FAKE_RECIEVER_UUID,
               self::FAKE_TRANSACTION_AMOUNT
           )
       );
    }

    public function testThatIfTwoUsersExistThereWillBeACallToTheUnderlyingService()
    {
        $fakeUser = new User(
            self::FAKE_ISSUER_UUID,
            'john doe',
            Balance::register(1000)
        );

        $anotherFakeUser =  new User(
            self::FAKE_RECIEVER_UUID,
            'john doe',
            Balance::register(2000)
        );



        $this->userRepositoryMock->findById(self::FAKE_ISSUER_UUID)
            ->shouldBeCalledTimes(1)
            ->willReturn($fakeUser);

        $this->userRepositoryMock->findById(self::FAKE_RECIEVER_UUID)
            ->shouldBeCalledTimes(1)
            ->willReturn($anotherFakeUser);

        $this->issueTransactionServiceMock->issue(
            Argument::exact($fakeUser),
            Argument::exact($anotherFakeUser),
            Argument::exact(self::FAKE_TRANSACTION_AMOUNT)
        )->shouldBeCalledTimes(1)
        ->willReturn(Transaction::register(
            self::FAKE_TRANSACTION_ID,
            self::FAKE_ISSUER_UUID,
            self::FAKE_RECIEVER_UUID,
            self::FAKE_TRANSACTION_AMOUNT
        ));

        $response = $this->issueTransactionUseCase->handle(
            new IssueTransactionRequest(
                self::FAKE_ISSUER_UUID,
                self::FAKE_RECIEVER_UUID,
                self::FAKE_TRANSACTION_AMOUNT
            )
        );

        $this->assertInstanceOf(IssueTransactionResponse::class, $response);

        $this->assertEquals(self::FAKE_TRANSACTION_ID, $response->getTransactionResource()->getId());
    }
}
