<?php

namespace App\Services\Transaction;

use Illuminate\Http\Response;
use App\Entities\Http\HttpRequest;
use App\Interfaces\Http\HttpRequestServiceInterface;
use App\Interfaces\Transaction\TransactionEntityInterface;
use App\Interfaces\Transaction\TransactionRepositoryInterface;
use App\Exceptions\Transaction\TransactionAuthorizationServiceException;
use App\Interfaces\Transaction\TransactionAuthorizationServiceInterface;

class TransactionAuthorizationService implements TransactionAuthorizationServiceInterface
{
    private $transactionRepository;
    private $httpRequestService;

    const AUTHORIZATION_URI = 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6';

    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        HttpRequestServiceInterface $httpRequestService
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->httpRequestService = $httpRequestService;
    }

    /**
     * Requests an external service to authorize the transaction
     *
     * Returns the transaction entity authorized or unauthorized
     *
     * @param TransactionEntityInterface $transaction
     * @return TransactionEntityInterface
     * @throws TransactionAuthorizationServiceException
     */
    public function authorize(TransactionEntityInterface $transaction): TransactionEntityInterface
    {
        $authResponse = $this->httpRequestService->get(
            new HttpRequest(self::AUTHORIZATION_URI, [], [
                'payer_id' => $transaction->getPayerId(),
                'payee_id' => $transaction->getPayeeId(),
                'amount' => $transaction->getAmount()
            ])
        );

        if ($authResponse->isServiceUnavailable()) {
            throw new TransactionAuthorizationServiceException(
                'O serviço de autorização de transação está indisponível no momento.',
                Response::HTTP_SERVICE_UNAVAILABLE
            );
        }

        if ($authResponse->getStatusCode() !== Response::HTTP_OK &&
            $authResponse->getStatusCode() !== Response::HTTP_UNAUTHORIZED
        ) {
            throw new TransactionAuthorizationServiceException(
                'Não foi possível autorizar a transação. Verifique os dados e tente novamente.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ($authResponse->getStatusCode() === Response::HTTP_OK) {
            $this->authorizeTransaction($transaction);

            return $transaction;
        }

        if ($authResponse->getStatusCode() === Response::HTTP_UNAUTHORIZED) {
            $this->forbidTransaction($transaction);
        }

        return $transaction;
    }

    /**
     * Authorizes the transaction and saves it in the database
     *
     * @param TransactionEntityInterface $transaction
     * @return void
     */
    protected function authorizeTransaction(TransactionEntityInterface $transaction)
    {
        $transaction->setAuthorizedStatus();
        $this->transactionRepository->save($transaction);
    }

    /**
     * Forbids the transaction and saves it in the database
     *
     * @param TransactionEntityInterface $transaction
     * @return void
     */
    protected function forbidTransaction(TransactionEntityInterface $transaction)
    {
        $transaction->setUnauthorizedStatus();
        $this->transactionRepository->save($transaction);
    }
}
