<?php

namespace App\Services\Transaction;

use Illuminate\Http\Response;
use App\Interfaces\Transaction\TransactionEntityInterface;
use App\Interfaces\Transaction\TransactionRepositoryInterface;
use App\Exceptions\Transaction\TransactionTransferServiceException;
use App\Interfaces\Transaction\TransactionTransferServiceInterface;
use App\Interfaces\Transaction\TransactionAuthorizationServiceInterface;
use App\Interfaces\User\UserRepositoryInterface;

class TransactionTransferService implements TransactionTransferServiceInterface
{
    private $transactionRepository;
    private $userRepository;
    private $transactionAuthorizationService;

    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        UserRepositoryInterface $userRepository,
        TransactionAuthorizationServiceInterface $transactionAuthorizationService
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->userRepository = $userRepository;
        $this->transactionAuthorizationService = $transactionAuthorizationService;
    }

    /**
     * Receives a transaction entity and make the transfer
     *
     * @param TransactionEntityInterface $transaction
     * @throws TransactionTransferServiceException
     * @return TransactionEntityInterface
     */
    public function transfer(TransactionEntityInterface $transaction): TransactionEntityInterface
    {

        $transaction = $this->getAuthorizedTransaction($transaction);

        if ($transaction->isUnauthorized()) {
            throw new TransactionTransferServiceException(
                'Transação não autorizada',
                Response::HTTP_UNAUTHORIZED
            );
        }

        $this->completeTransfer($transaction);

        return $transaction;

    }

    /**
     * Saves the transaction in the database
     *
     * @param TransactionEntityInterface $transaction
     * @return TransactionEntityInterface
     */
    protected function saveTransaction(TransactionEntityInterface $transaction): self
    {
        $this->transactionRepository->save($transaction);

        return $this;
    }

    /**
     * Returns an transaction with status authorized or unauthorized
     *
     * @param TransactionEntityInterface $transaction
     * @return TransactionEntityInterface
     */
    protected function getAuthorizedTransaction(TransactionEntityInterface $transaction): TransactionEntityInterface
    {
        $transaction = $this->transactionAuthorizationService->authorize($transaction);

        return $transaction;
    }

    /**
     * Completes the transfer and saves the transaction in the database
     *
     * @param TransactionEntityInterface $transaction
     * @return TransactionEntityInterface
     */
    protected function completeTransfer(TransactionEntityInterface $transaction): self
    {
        $transaction->transfer();
        $this->transactionRepository->save($transaction);
        $this->userRepository->save($transaction->getPayer());
        $this->userRepository->save($transaction->getPayee());

        return $this;
    }

}
