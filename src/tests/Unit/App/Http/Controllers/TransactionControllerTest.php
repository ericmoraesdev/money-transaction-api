<?php

namespace Tests\Unit\App\Http\Controllers;

use App\Exceptions\EntityException;
use App\Exceptions\RepositoryException;
use App\Exceptions\RequestException;
use App\Exceptions\ServiceException;
use TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\TransactionController;
use App\Interfaces\Transaction\TransactionEntityInterface;
use App\Interfaces\Transaction\TransactionRequestInterface;
use App\Interfaces\Transaction\TransactionServiceInterface;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TransactionControllerTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * test should return json response with data and message when transaction is successful
     * @covers TransactionController::transaction()
     */
    public function testShouldReturnJsonResponseWithDataAndMessageWhenTransactionIsSuccessful(): void
    {
        ## ARRANGE
        $transactionRequest = $this->createTransactionRequestMock();
        $transactionRequest
            ->expects($this->once())
            ->method('validate')
            ->willReturn($this->createRequestMock());

        $transactionService = $this->createTransactionServiceMock();
        $transactionService
            ->expects($this->once())
            ->method('transact')
            ->willReturn($this->createTransactionEntityMock());

        ## ACT
        $transaction = (new TransactionController())->transaction(
            $transactionRequest,
            $transactionService
        );

        ## ASSERTION
        $this->assertInstanceOf(JsonResponse::class, $transaction);
        $this->assertEquals(Response::HTTP_CREATED, $transaction->getStatusCode());
        $this->assertTrue(
            isset(
                $transaction->getOriginalContent()['data'],
                $transaction->getOriginalContent()['message']
            ) &&
            $transaction->getOriginalContent()['data'] instanceof TransactionEntityInterface &&
            $transaction->getOriginalContent()['message'] === 'Transação realizada com sucesso!'
        );
    }

    /**
     * test should return json response with message and errors when throw request exception
     * @covers TransactionController::transaction()
     */
    public function testShouldReturnJsonResponseWithMessageAndErrorsWhenThrowRequestException()
    {
        ## ARRANGE
        $transactionRequest = $this->createTransactionRequestMock();
        $transactionRequest
            ->expects($this->once())
            ->method('validate')
            ->willThrowException(
                new RequestException(
                    '{"payer_id": "error"}',
                    Response::HTTP_UNPROCESSABLE_ENTITY
                )
            );

        ## ACT
        $transaction = (new TransactionController())->transaction(
            $transactionRequest,
            $this->createTransactionServiceMock()
        );

        ## ASSERTION
        $this->assertInstanceOf(JsonResponse::class, $transaction);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $transaction->getStatusCode());
        $this->assertTrue(
            isset(
                $transaction->getOriginalContent()['message'],
                $transaction->getOriginalContent()['errors'],
                $transaction->getOriginalContent()['errors']->payer_id
            ) &&
            $transaction->getOriginalContent()['message'] === 'É necessário validar os dados da transação.' &&
            $transaction->getOriginalContent()['errors']->payer_id === 'error'
        );
    }

     /**
     * @dataProvider provideMessageException
     */
    public function testShouldReturnJsonResponseWithMessageWhenThrowException($exception)
    {
        ## ARRANGE
        $transactionRequest = $this->createTransactionRequestMock();
        $transactionRequest
            ->expects($this->once())
            ->method('validate')
            ->willThrowException($exception);

        ## ACT
        $transaction = (new TransactionController())->transaction(
            $transactionRequest,
            $this->createTransactionServiceMock()
        );

        ## ASSERTION
        $this->assertInstanceOf(JsonResponse::class, $transaction);
        $this->assertTrue(
            isset($transaction->getOriginalContent()['message']) &&
            $transaction->getOriginalContent()['message'] === $exception->getMessage()
        );
    }

    /**
     * test should return json response with default message when throw any other exception
     * @covers TransactionController::transaction()
     */
    public function testShouldReturnJsonResponseWithDefaultMessageWhenThrowAnyOtherException()
    {
        $message = 'teste...';

        ## ARRANGE
        $transactionRequest = $this->createTransactionRequestMock();
        $transactionRequest
            ->expects($this->once())
            ->method('validate')
            ->willThrowException(new \Exception('teste...'));

        ## ACT
        $transaction = (new TransactionController())->transaction(
            $transactionRequest,
            $this->createTransactionServiceMock()
        );

        ## ASSERTION
        $this->assertInstanceOf(JsonResponse::class, $transaction);
        $this->assertTrue(
            isset($transaction->getOriginalContent()['message']) &&
            $transaction->getOriginalContent()['message'] != $message
        );
    }

    /**
     * Creates a new TransactionRequest mock.
     *
     * @return PHPUnit\Framework\MockObject\MockObject|TransactionRequestInterface
     */
    private function createTransactionRequestMock()
    {
        return $this->createMock(TransactionRequestInterface::class);
    }

    /**
     * Creates a new Request mock.
     *
     * @return Request
     */
    private function createRequestMock(
        array $content = [
            'payer_id' => 1,
            'payee_id' => 2,
            'amount' => 100
        ]
    ): Request
    {
        return request()->merge($content);
    }

    /**
     * Creates a new TransactionService mock.
     *
     * @return PHPUnit\Framework\MockObject\MockObject|TransactionServiceInterface
     */
    private function createTransactionServiceMock()
    {
        return $this->createMock(TransactionServiceInterface::class);
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

    public function provideMessageException()
    {
        return [
            [
                new EntityException(
                    'É necessário validar o dado da entidade'
                )
            ],
            [
                new ServiceException(
                    'Erro na hora de executar o serviço.',
                )
            ],
            [
                new RepositoryException(
                    'Erro na hora de salvar os dados na base de dados.'
                )
            ],

        ];
    }
}
