<?php
namespace Tests\Integration\App\Http\Controllers;

use App\Exceptions\Transaction\TransactionAuthorizationServiceException;
use App\Interfaces\Http\HttpRequestServiceInterface;
use App\Interfaces\Http\HttpResponseEntityInterface;
use App\Interfaces\Transaction\TransactionAuthorizationServiceInterface;
use TestCase;
use App\Models\User\User;
use Illuminate\Http\Response;

class TransactionControllerTest extends TestCase
{

     /**
     * testing wrong transaction request
     *
     * @return void
     */
    public function testShouldGetErrorsWhenSendingWrongRequest()
    {
        $this->json('POST', '/transaction', [
            'payer_id' => 'wrong',
            'payee_id' => 'wrong',
            'amount' => 'wrong'

        ])->seeJsonStructure([
            'errors' => [
                'payer_id',
                'payee_id',
                'amount'
            ]
        ]);
    }

    /**
     * testing wrong transaction request
     *
     * @return void
     */
    public function testShouldGetErrorsWhenSendingPayerSameASPayeeOnRequest()
    {
        $this->json('POST', '/transaction', [
            'payer_id' => 1,
            'payee_id' => 1,
            'amount' => 100.23

        ])->seeJsonStructure([
            'errors' => [
                'payer_id',
                'payee_id'
            ]
        ]);
    }

    /**
     * testing wrong transaction request
     *
     * @return void
     */
    public function testShouldGetErrorsWhenSendingAmountLesserThanMinimumOnRequest()
    {
        $this->json('POST', '/transaction', [
            'payer_id' => 1,
            'payee_id' => 2,
            'amount' => 0

        ])->seeJsonStructure([
            'errors' => [
                'amount'
            ]
        ]);
    }

    /**
     * testing shopkeeper trying transaction
     *
     * @return void
     */
    public function testShouldNotTransactWhenPayerDoesNotExists()
    {


        $payee = User::factory()->create();
        $unexistentPayerId = $payee->id + 50;

        $this->json('POST', '/transaction', [
            'payer_id' => $unexistentPayerId,
            'payee_id' => $payee->id,
            'amount' => 100
        ])->seeJson([
            'message' => 'O usuário pagador informado não existe.'
        ]);
    }

    /**
     * testing shopkeeper trying transaction
     *
     * @return void
     */
    public function testShouldNotTransactWhenPayeeDoesNotExists()
    {

        $payer = User::factory()->create([
            'available_money' => 100
        ]);
        $unexistentPayeeId = $payer->id + 50;

        $this->json('POST', '/transaction', [
            'payer_id' => $payer->id,
            'payee_id' => $unexistentPayeeId,
            'amount' => 100
        ])->seeJson([
            'message' => 'O usuário a ser pago informado não existe.'
        ]);
    }

    /**
     * testing shopkeeper trying transaction
     *
     * @return void
     */
    public function testShouldNotTransactWhenPayerIsShopkeeper()
    {

        $shopkeeper = User::factory()->shopkeeper()->create();
        $commonUser = User::factory()->create();


        $this->json('POST', '/transaction', [
            'payer_id' => $shopkeeper->id,
            'payee_id' => $commonUser->id,
            'amount' => 100
        ])->seeJson([
            'message' => 'Este usuário não pode realizar essa transação.'
        ]);

    }

    /**
     * testing shopkeeper trying transaction
     *
     * @return void
     */
    public function testShouldNotTransactWhenPayerDoesNotHaveCpfAndCnpj()
    {

        $payer = User::factory()->create([
            'cpf' => null,
            'cnpj' => null
        ]);

        $unexistentPayeeId = $payer->id + 50;

        $this->json('POST', '/transaction', [
            'payer_id' => $payer->id,
            'payee_id' => $unexistentPayeeId,
            'amount' => 100
        ])->seeJson([
            'message' => 'É necessário informar o CPF ou CNPJ do usuário'
        ]);
    }

    /**
     * testing shopkeeper trying transaction
     *
     * @return void
     */
    public function testShouldNotTransactWhenPayerNotHasMoneyEnough()
    {

        $payer = User::factory()->create([
            'available_money' => 99
        ]);

        $payee = User::factory()->create();

        $this->json('POST', '/transaction', [
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'amount' => 100
        ])->seeJson([
            'message' => 'O usuário pagador não possui saldo suficiente.'
        ]);
    }

    /**
     * testing authorization service unavailable
     */
    public function testShouldNotTransactWhenAuthorizationServiceUnavailable()
    {
        ## ARRANGE
        $payer = User::factory()->create([
            'available_money' => 100
        ]);

        $payee = User::factory()->create();

        $authMock = $this->createMock(TransactionAuthorizationServiceInterface::class);
        $authMock->method('authorize')->willThrowException(
            new TransactionAuthorizationServiceException(
                'O serviço de autorização de transação está indisponível no momento.',
                Response::HTTP_SERVICE_UNAVAILABLE
            )
        );

        $this->app->instance(TransactionAuthorizationServiceInterface::class, $authMock);

        ## ACT & ASSERT
        $this->json('POST', '/transaction', [
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'amount' => 100
        ])->seeJson([
            'message' => 'O serviço de autorização de transação está indisponível no momento.'
        ]);
    }

    /**
     * testing authorization service unable to process the request
     */
    public function testShouldNotTransactWhenAuthorizationServiceIsUnableToProcessTheRequest()
    {
        ## ARRANGE
        $payer = User::factory()->create([
            'available_money' => 100
        ]);

        $payee = User::factory()->create();

        $httpResponseMock = $this->createMock(HttpResponseEntityInterface::class);
        $httpResponseMock->method('getStatusCode')->willReturn(Response::HTTP_BAD_REQUEST);

        $httpRequestMock = $this->createMock(HttpRequestServiceInterface::class);
        $httpRequestMock->method('get')->willReturn($httpResponseMock);

        $this->app->instance(HttpRequestServiceInterface::class, $httpRequestMock);


        ## ACT & ASSERT
        $this->json('POST', '/transaction', [
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'amount' => 100
        ])->seeJson([
            'message' => 'Não foi possível autorizar a transação. Verifique os dados e tente novamente.'
        ]);
    }

    /**
     * testing authorization service forbidding
     */
    public function testShouldNotTransactWhenAuthorizationServiceForbids()
    {
        ## ARRANGE
        $payer = User::factory()->create([
            'available_money' => 100
        ]);

        $payee = User::factory()->create();

        $httpResponseMock = $this->createMock(HttpResponseEntityInterface::class);
        $httpResponseMock->method('getStatusCode')->willReturn(Response::HTTP_UNAUTHORIZED);

        $httpRequestMock = $this->createMock(HttpRequestServiceInterface::class);
        $httpRequestMock->method('get')->willReturn($httpResponseMock);

        $this->app->instance(HttpRequestServiceInterface::class, $httpRequestMock);

        ## ACT & ASSERT
        $this->json('POST', '/transaction', [
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'amount' => 100
        ])->seeJson([
            'message' => 'Transação não autorizada.'
        ]);
    }

    /**
     * testing correct transaction
     *
     * @return void
     */
    public function testShouldTransact()
    {
        ## ARRANGE
        $payer = User::factory()->create([
            'available_money' => 100
        ]);

        $payee = User::factory()->create();

        $httpResponseMock = $this->createMock(HttpResponseEntityInterface::class);
        $httpResponseMock->method('getStatusCode')->willReturn(Response::HTTP_OK);

        $httpRequestMock = $this->createMock(HttpRequestServiceInterface::class);
        $httpRequestMock->method('get')->willReturn($httpResponseMock);

        $this->app->instance(HttpRequestServiceInterface::class, $httpRequestMock);

        ## ACT & ASSERT
        $this->json('POST', '/transaction', [
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'amount' => 100
        ])->seeStatusCode(Response::HTTP_CREATED);

    }


}
