<?php

namespace Tests\Unit\App\Http\Controllers;

use App\Exceptions\Transaction\TransactionRequestException;
use TestCase;
use Illuminate\Http\Request;
use App\Http\Requests\TransactionRequest;

class TransactionRequestTest extends TestCase
{
    /**
     * test should validate and throw transactionRequestException when fails
     * @covers TransactionRequest::validate()
     */
    public function testShouldValidateAndThrowTransactionRequestExceptionWhenFails(): void
    {
        ## ARRANGE
        $request = $this->createRequestMock([
            'payer_id' => '',
            'payee_id' => '',
            'amount' => ''
        ]);

        ## ASSERTION
        $this->expectException(TransactionRequestException::class);

        ## ACT
        $transactionRequest = new TransactionRequest($request);
        $transactionRequest->validate();

    }

    /**
     * test should return request when validate is successful
     * @covers TransactionRequest::validate()
     */
    public function testShouldReturnRequestWhenValidateIsSuccessful(): void
    {
        ## ARRANGE
        $request = $this->createRequestMock([
            'payer_id' => '1',
            'payee_id' => '2',
            'amount' => '100'
        ]);

        ## ACT
        $transactionRequest = new TransactionRequest($request);
        $transactionRequest->validate();

        ## ASSERTION
        $this->assertInstanceOf(Request::class, $transactionRequest->request);
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
}
