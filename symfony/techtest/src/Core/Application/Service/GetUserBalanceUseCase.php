<?php

namespace App\Core\Application\Service;

use App\Core\Domain\Entity\User;
use App\Core\Domain\Exception\UserNotFoundException;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\SharedKernel\Application\Request;
use App\SharedKernel\Application\Response;
use App\SharedKernel\Application\UseCaseInterface;
use App\SharedKernel\Domain\ValueObject\EntityId;

class GetUserBalanceUseCase implements UseCaseInterface
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @var GetUserBalanceRequest | Request $request
     * @returns GetUserBalanceResponse
     */
    public function handle(Request $request): Response
    {
        /** @var User $user */
        $user = $this->userRepository->findById($request->getUserId());

        if (null == $user) {
            throw new UserNotFoundException("User with id ".$request->getUserId(). "was not found");
        }

        return new GetUserBalanceResponse(
            $user->getBalance()

        );
    }
}