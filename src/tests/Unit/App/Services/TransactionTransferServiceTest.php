<?php

namespace Tests\Unit\App\Http\Controllers;

use App\Exceptions\Transaction\TransactionTransferServiceException;
use TestCase;
use App\Interfaces\Transaction\TransactionEntityInterface;
use App\Interfaces\Transaction\TransactionAuthorizationServiceInterface;
use App\Interfaces\Transaction\TransactionRepositoryInterface;
use App\Interfaces\User\UserEntityInterface;
use App\Interfaces\User\UserRepositoryInterface;
use App\Services\Transaction\TransactionTransferService;

class TransactionTransferServiceTest extends TestCase
{
    /**
     * test should transfer when authorized
     * @covers TransactionTransferService::transfer()
     */
    public function testShouldTransferWhenAuthorized(): void
    {
        ## ARRANGE
        $transactionRepository = $this->createMock(TransactionRepositoryInterface::class);
        $transactionRepository->method('save')->willReturn(
            $this->createMock(TransactionEntityInterface::class)
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('save')->willReturn(
            $this->createMock(UserEntityInterface::class)
        );

        $transactionEntity = $this->createMock(TransactionEntityInterface::class);
        $transactionEntity->method('isUnauthorized')->willReturn(false);
        $transactionEntity->method('isAuthorized')->willReturn(true);
        $transactionEntity->method('getPayer')->willReturn(
            $this->createMock(UserEntityInterface::class)
        );
        $transactionEntity->method('getPayee')->willReturn(
            $this->createMock(UserEntityInterface::class)
        );

        $transactionAuthorizationService = $this->createMock(TransactionAuthorizationServiceInterface::class);
        $transactionAuthorizationService->method('authorize')->willReturn(
            $transactionEntity
        );

        ## ACT
        $transaction = (new TransactionTransferService(
            $transactionRepository,
            $userRepository,
            $transactionAuthorizationService
        ))->transfer($transactionEntity);

        ## ASSERT
        $this->assertInstanceOf(TransactionEntityInterface::class, $transaction);

    }

    /**
     * test should throw transactionTransferServiceException when not authorized
     * @covers TransactionService::transact()
     */
    public function testShouldThrowTransactionTransferServiceExceptionWhenNotAuthorized(): void
    {

        ## ARRANGE
        $transactionRepository = $this->createMock(TransactionRepositoryInterface::class);
        $transactionRepository->method('save')->willReturn(
            $this->createMock(TransactionEntityInterface::class)
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('save')->willReturn(
            $this->createMock(UserEntityInterface::class)
        );

        $transactionEntity = $this->createMock(TransactionEntityInterface::class);
        $transactionEntity->method('isUnauthorized')->willReturn(true);
        $transactionEntity->method('isAuthorized')->willReturn(false);

        $transactionAuthorizationService = $this->createMock(
            TransactionAuthorizationServiceInterface::class
        );
        $transactionAuthorizationService->method('authorize')->willReturn(
            $transactionEntity
        );

        ## ASSERT
        $this->expectException(TransactionTransferServiceException::class);

        ## ACT
        (new TransactionTransferService(
            $transactionRepository,
            $userRepository,
            $transactionAuthorizationService
        ))->transfer($transactionEntity);

    }

}
