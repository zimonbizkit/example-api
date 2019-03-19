<?php

namespace Tests\Core\Application\Service;

use App\Core\Application\Service\GetUserBalanceRequest;
use App\Core\Application\Service\GetUserBalanceResponse;
use App\Core\Application\Service\GetUserBalanceUseCase;
use App\Core\Domain\Entity\Balance;
use App\Core\Domain\Entity\User;
use App\Core\Domain\Exception\UserNotFoundException;
use App\Core\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class GetUserBalanceUseCaseTest extends TestCase
{
    const FAKE_UUID='330b08fb-7132-4ad3-8928-6c16c5c69b20';
    const FAKE_NAME='john doe';
    const FAKE_BALANCE_VALUE = 1000;

    /** @var UserRepositoryInterface | ObjectProphecy */
    private $userRepositoryMock;

    /** @var GetUserBalanceUseCase */
    private $getUserBalanceUseCase;

    public function setUp(): void
    {
        $this->userRepositoryMock = $this->prophesize(UserRepositoryInterface::class);
        $this->getUserBalanceUseCase = new GetUserBalanceUseCase(
            $this->userRepositoryMock->reveal()
        );
    }

    public function tearDown(): void
    {
        unset(
            $this->getUserBalanceUseCase,
            $this->userRepositoryMock
        );
    }

    public function testThatIfNoUserHasBeenFountThenAnSpecificExceptionWillBeThrown()
    {
        $fakeRequest = new GetUserBalanceRequest(
            self::FAKE_UUID
        );

        $this->userRepositoryMock->findById(
            $fakeRequest->getUserId()
        )->shouldBeCalledTimes(1)
            ->willReturn(null);

        $this->expectException(UserNotFoundException::class);
        $this->getUserBalanceUseCase->handle(
            $fakeRequest
        );
    }

    public function testThatIfTheUserIsPresentThenAnSpecificResponseWillBeReturned()
    {
        $fakeUser = new User(
            self::FAKE_UUID,
            self::FAKE_NAME,
            Balance::register(self::FAKE_BALANCE_VALUE)
        );

        $fakeRequest = new GetUserBalanceRequest(
            self::FAKE_UUID
        );

        $this->userRepositoryMock->findById(
            $fakeRequest->getUserId()
        )->shouldBeCalledTimes(1)
            ->willReturn(
                $fakeUser
            );

        $response = $this->getUserBalanceUseCase->handle(
            $fakeRequest
        );

        $this->assertInstanceOf(GetUserBalanceResponse::class, $response);
        $this->assertEquals(self::FAKE_BALANCE_VALUE, $response->getBalanceResource()->getValue());
    }
}
