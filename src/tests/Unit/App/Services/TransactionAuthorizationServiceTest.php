<?php

namespace Tests\Unit\App\Http\Controllers;

use TestCase;
use Illuminate\Http\Response;
use App\Interfaces\User\UserEntityInterface;
use App\Interfaces\Http\HttpRequestServiceInterface;
use App\Interfaces\Http\HttpResponseEntityInterface;
use App\Services\Transaction\TransactionAuthorization;
use App\Interfaces\Transaction\TransactionEntityInterface;
use App\Services\Transaction\TransactionAuthorizationService;
use App\Interfaces\Transaction\TransactionRepositoryInterface;
use App\Exceptions\Transaction\TransactionAuthorizationServiceException;
use App\Interfaces\Transaction\TransactionAuthorizationServiceInterface;

class TransactionAuthorizationServiceTest extends TestCase
{
    /**
     * test should authorize transaction when external service returns ok
     * @covers TransactionAuthorization::authorize()
     */
    public function testShouldAuthorizeTransactionWhenExternalServiceReturnsOk(): void
    {
        ## ARRANGE
        $transactionRepository = $this->createMock(TransactionRepositoryInterface::class);
        $transactionRepository->method('save')->willReturn(
            $this->createMock(TransactionEntityInterface::class)
        );

        $payer = $this->app->make(UserEntityInterface::class);
        $payer->setAvailableMoney(100.00);

        $transactionEntity = $this->app->make(TransactionEntityInterface::class);
        $transactionEntity
            ->setUnauthorizedStatus()
            ->setPayer($payer)
            ->setPayee($this->app->make(UserEntityInterface::class))
            ->setAmount(100.00);


        $httpResponse = $this->createMock(HttpResponseEntityInterface::class);
        $httpResponse->method('getStatusCode')->willReturn(Response::HTTP_OK);

        $httpRequestService = $this->createMock(HttpRequestServiceInterface::class);
        $httpRequestService->method('get')->willReturn($httpResponse);


        ## ACT
        $transaction = (new TransactionAuthorizationService(
            $transactionRepository,
            $httpRequestService
        ))->authorize($transactionEntity);

        ## ASSERT
        $this->assertInstanceOf(TransactionEntityInterface::class, $transaction);
        $this->assertTrue($transaction->isAuthorized());

    }

    /**
     * test should authorize transaction when external service returns ok
     * @covers TransactionAuthorization::authorize()
     */
    public function testShouldForbidTransactionWhenExternalServiceReturnsUnauthorized(): void
    {
        ## ARRANGE
        $transactionRepository = $this->createMock(TransactionRepositoryInterface::class);
        $transactionRepository->method('save')->willReturn(
            $this->createMock(TransactionEntityInterface::class)
        );

        $payer = $this->createMock(UserEntityInterface::class);
        $payer->method('getId')->willReturn(1);
        $payer->method('hasEnoughMoney')->willReturn(true);
        $payer->method('isAllowedToPay')->willReturn(true);
        $payer->method('getAvailableMoney')->willReturn(100.00);

        $transactionEntity = $this->app->make(TransactionEntityInterface::class);
        $transactionEntity
            ->setPayer($payer)
            ->setPayee($this->app->make(UserEntityInterface::class))
            ->setAmount(100)
            ->setAuthorizedStatus();

        $httpResponse = $this->createMock(HttpResponseEntityInterface::class);
        $httpResponse->method('getStatusCode')->willReturn(Response::HTTP_UNAUTHORIZED);

        $httpRequestService = $this->createMock(HttpRequestServiceInterface::class);
        $httpRequestService->method('get')->willReturn($httpResponse);


        ## ACT
        $transaction = (new TransactionAuthorizationService(
            $transactionRepository,
            $httpRequestService
        ))->authorize($transactionEntity);

        ## ASSERT
        $this->assertInstanceOf(TransactionEntityInterface::class, $transaction);
        $this->assertTrue($transaction->isUnauthorized());

    }

    /**
     * test should throw exception when external service is unavailable
     * @covers TransactionAuthorization::authorize()
     */
    public function testShouldThrowExceptionWhenExternalServiceIsUnavailable(): void
    {
        ## ARRANGE
        $transactionRepository = $this->createMock(TransactionRepositoryInterface::class);
        $transactionRepository->method('save')->willReturn(
            $this->createMock(TransactionEntityInterface::class)
        );

        $transactionEntity = $this->createMock(TransactionEntityInterface::class);
        $transactionEntity->method('isUnauthorized')->willReturn(false);
        $transactionEntity->method('isAuthorized')->willReturn(true);

        $httpResponse = $this->createMock(HttpResponseEntityInterface::class);
        $httpResponse->method('isServiceUnavailable')->willReturn(true);

        $httpRequestService = $this->createMock(HttpRequestServiceInterface::class);
        $httpRequestService->method('get')->willReturn($httpResponse);

        ## ASSERT
        $this->expectException(TransactionAuthorizationServiceException::class);

        ## ACT
        (new TransactionAuthorizationService(
            $transactionRepository,
            $httpRequestService
        ))->authorize($transactionEntity);

    }


}
