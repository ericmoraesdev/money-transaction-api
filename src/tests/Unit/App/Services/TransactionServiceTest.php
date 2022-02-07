<?php

namespace Tests\Unit\App\Http\Controllers;

use App\Exceptions\Transaction\TransactionServiceException;
use TestCase;
use App\Interfaces\User\UserRepositoryInterface;
use App\Services\Transaction\TransactionService;
use App\Interfaces\Transaction\TransactionEntityInterface;
use App\Interfaces\Notification\NotificationEntityInterface;
use App\Interfaces\Notification\NotificationServiceInterface;
use App\Interfaces\Transaction\TransactionRepositoryInterface;
use App\Interfaces\Transaction\TransactionTransferServiceInterface;
use App\Interfaces\User\UserEntityInterface;

class TransactionServiceTest extends TestCase
{
    /**
     * test should transact when all data is valid.
     * @covers TransactionService::transact()
     */
    public function testShouldTransactWhenAllDataIsValid(): void
    {
        ## ARRANGE
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('getById')->willReturn(
            $this->createMock(UserEntityInterface::class)
        );

        $transactionEntity = $this->createMock(TransactionEntityInterface::class);
        $transactionEntity->method('getPayer')->willReturn(
            $this->createMock(UserEntityInterface::class)
        );
        $transactionEntity->method('getPayee')->willReturn(
            $this->createMock(UserEntityInterface::class)
        );

        $transactionTransferService = $this->createTransactionTransferServiceMock();
        $transactionTransferService
            ->expects($this->once())
            ->method('transfer')
            ->willReturn($this->createTransactionEntityMock());

        $notificationService = $this->createNotificationServiceMock();
        $notificationService
            ->expects($this->once())
            ->method('notify');

        ## ACT
        $transaction = (new TransactionService(
            $userRepository,
            $this->createMock(TransactionRepositoryInterface::class),
            $transactionEntity,
            $transactionTransferService,
            $this->createMock(NotificationEntityInterface::class),
            $notificationService
        ))->transact(1, 2, 100);

        ## ASSERT
        $this->assertInstanceOf(TransactionEntityInterface::class, $transaction);

    }

    /**
     * test should throw transactionServiceException when payer is same as payee
     * @covers TransactionService::transact()
     */
    public function testShouldThrowTransactionServiceExceptionWhenPayerIsSameAsPayee(): void
    {
        ## ARRANGE
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $transactionTransferService = $this->createTransactionTransferServiceMock();
        $notificationService = $this->createNotificationServiceMock();

        ## ASSERT
        $this->expectException(TransactionServiceException::class);

        ## ACT
        (new TransactionService(
            $userRepository,
            $this->createMock(TransactionRepositoryInterface::class),
            $this->createMock(TransactionEntityInterface::class),
            $transactionTransferService,
            $this->createMock(NotificationEntityInterface::class),
            $notificationService
        ))->transact(1, 1, 100);

    }

    /**
     * test should throw transactionServiceException when payer id is lesser than 1
     * @covers TransactionService::transact()
     */
    public function testShouldThrowTransactionServiceExceptionWhenPayerIdIsLessesThan1(): void
    {
        ## ARRANGE
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $transactionTransferService = $this->createTransactionTransferServiceMock();
        $notificationService = $this->createNotificationServiceMock();

        ## ASSERT
        $this->expectException(TransactionServiceException::class);

        ## ACT
        (new TransactionService(
            $userRepository,
            $this->createMock(TransactionRepositoryInterface::class),
            $this->createMock(TransactionEntityInterface::class),
            $transactionTransferService,
            $this->createMock(NotificationEntityInterface::class),
            $notificationService
        ))->transact(0, 1, 100);

    }

     /**
     * test should throw transactionServiceException when payee id is lesser than 1
     * @covers TransactionService::transact()
     */
    public function testShouldThrowTransactionServiceExceptionWhenPayeeIdIsLessesThan1(): void
    {
        ## ARRANGE
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $transactionTransferService = $this->createTransactionTransferServiceMock();
        $notificationService = $this->createNotificationServiceMock();

        ## ASSERT
        $this->expectException(TransactionServiceException::class);

        ## ACT
        (new TransactionService(
            $userRepository,
            $this->createMock(TransactionRepositoryInterface::class),
            $this->createMock(TransactionEntityInterface::class),
            $transactionTransferService,
            $this->createMock(NotificationEntityInterface::class),
            $notificationService
        ))->transact(1, 0, 100);

    }

     /**
     * test should throw transactionServiceException when payee id is lesser than 1
     * @covers TransactionService::transact()
     */
    public function testShouldThrowTransactionServiceExceptionWhenAmountIsLessesThan1(): void
    {
        ## ARRANGE
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $transactionTransferService = $this->createTransactionTransferServiceMock();
        $notificationService = $this->createNotificationServiceMock();

        ## ASSERT
        $this->expectException(TransactionServiceException::class);

        ## ACT
        (new TransactionService(
            $userRepository,
            $this->createMock(TransactionRepositoryInterface::class),
            $this->createMock(TransactionEntityInterface::class),
            $transactionTransferService,
            $this->createMock(NotificationEntityInterface::class),
            $notificationService
        ))->transact(1, 2, 0);

    }

    /**
     * test should throw transactionServiceException when payer is not found
     * @covers TransactionService::transact()
     */
    public function testShouldThrowTransactionServiceExceptionWhenPayerIsNotFound(): void
    {
        ## ARRANGE
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('getById')->willReturn(null);
        $transactionTransferService = $this->createTransactionTransferServiceMock();
        $notificationService = $this->createNotificationServiceMock();

        ## ASSERT
        $this->expectException(TransactionServiceException::class);

        ## ACT
        (new TransactionService(
            $userRepository,
            $this->createMock(TransactionRepositoryInterface::class),
            $this->createMock(TransactionEntityInterface::class),
            $transactionTransferService,
            $this->createMock(NotificationEntityInterface::class),
            $notificationService
        ))->transact(1, 2, 100);
    }

     /**
     * test should throw transactionServiceException when payee is not found
     * @covers TransactionService::transact()
     */
    public function testShouldThrowTransactionServiceExceptionWhenPayeeIsNotFound(): void
    {
        ## ARRANGE
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository->method('getById')
            ->willReturnOnConsecutiveCalls(
                $this->createMock(UserEntityInterface::class),
                null
            );

        $transactionTransferService = $this->createTransactionTransferServiceMock();
        $notificationService = $this->createNotificationServiceMock();

        ## ASSERT
        $this->expectException(TransactionServiceException::class);

        ## ACT
        (new TransactionService(
            $userRepository,
            $this->createMock(TransactionRepositoryInterface::class),
            $this->createMock(TransactionEntityInterface::class),
            $transactionTransferService,
            $this->createMock(NotificationEntityInterface::class),
            $notificationService
        ))->transact(1, 2, 100);
    }

    /**
     * Creates a new TransactionTransferService mock.
     *
     * @return PHPUnit\Framework\MockObject\MockObject|TransactionTransferServiceInterface
     */
    private function createTransactionTransferServiceMock()
    {
        return $this->createMock(TransactionTransferServiceInterface::class);
    }

    /**
     * Creates a new TransactionEntity mock.
     *
     * @return PHPUnit\Framework\MockObject\MockObject|TransactionEntityInterface
     */
    private function createTransactionEntityMock()
    {
        return $this->createMock(TransactionEntityInterface::class);
    }

    /**
     * Creates a new NotificationService mock.
     *
     * @return PHPUnit\Framework\MockObject\MockObject|NotificationServiceInterface
     */
    private function createNotificationServiceMock()
    {
        return $this->createMock(NotificationServiceInterface::class);
    }

}
