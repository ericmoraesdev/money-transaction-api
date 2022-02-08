<?php

namespace App\Services\Transaction;

use Illuminate\Http\Response;
use App\Interfaces\User\UserRepositoryInterface;
use App\Interfaces\Transaction\TransactionEntityInterface;
use App\Exceptions\Transaction\TransactionServiceException;
use App\Exceptions\Transaction\TransactionTransferServiceException;
use App\Interfaces\Transaction\TransactionServiceInterface;
use App\Interfaces\Notification\NotificationEntityInterface;
use App\Interfaces\Notification\NotificationServiceInterface;
use App\Interfaces\Transaction\TransactionRepositoryInterface;
use App\Interfaces\Transaction\TransactionTransferServiceInterface;
use Illuminate\Support\Facades\DB;

class TransactionService implements TransactionServiceInterface
{

    private $userRepository;
    private $transactionRepository;
    private $transactionEntity;
    private $transactionTransferService;
    private $notificationEntity;
    private $notificationService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        TransactionRepositoryInterface $transactionRepository,
        TransactionEntityInterface $transactionEntity,
        TransactionTransferServiceInterface $transactionTransferService,
        NotificationEntityInterface $notificationEntity,
        NotificationServiceInterface $notificationService
    ) {
        $this->userRepository = $userRepository;
        $this->transactionRepository = $transactionRepository;
        $this->transactionEntity = $transactionEntity;
        $this->transactionTransferService = $transactionTransferService;
        $this->notificationEntity = $notificationEntity;
        $this->notificationService = $notificationService;
    }

    public function transact(int $payerId, int $payeeId, $amount): TransactionEntityInterface
    {
        $transaction = DB::transaction(function() use ($payerId, $payeeId, $amount) {
            $this->validate($payerId, $payeeId, $amount);

            // TODO: Check if user did the same transaction on interval

            $transaction = $this->createPendingTransaction($payerId, $payeeId, $amount);
            $this->transactionTransferService->transfer($transaction);
            return $transaction;
        });

        $this->notifyPayee($transaction);

        return $transaction;

    }

    protected function validate($payerId, $payeeId, $amount): self
    {
        if ($payerId === $payeeId) {
            throw new TransactionServiceException(
                'O usuário pagador precisa ser diferente do usuário a ser pago.',
                Response::HTTP_BAD_REQUEST
            );
        }

        $this->validatePayer($payerId);
        $this->validatePayee($payeeId);
        $this->validateAmount($amount);

        return $this;
    }

    protected function validatePayer(int $payerId): self
    {
        if ($payerId <= 0) {
            throw new TransactionServiceException(
                'Favor informar um usuário pagador válido.',
                Response::HTTP_BAD_REQUEST
            );
        }

        return $this;
    }

    protected function validatePayee(int $payeeId): self
    {
        if ($payeeId <= 0) {
            throw new TransactionServiceException(
                'Favor informar um usuário a ser pago válido.',
                Response::HTTP_BAD_REQUEST
            );
        }

        return $this;
    }

    protected function validateAmount(float $amount): self
    {
        if ($amount <= 0) {
            throw new TransactionServiceException(
                'Favor informar um valor maior que zero para realizar a transaçao.',
                Response::HTTP_BAD_REQUEST
            );
        }

        return $this;
    }

    protected function createPendingTransaction(
        int $payerId,
        int $payeeId,
        float $amount
    ): TransactionEntityInterface
    {
        $this->setPayerOnTransaction($payerId);
        $this->setPayeeOnTransaction($payeeId);
        $this->transactionEntity->setAmount($amount);
        $this->transactionEntity->setPendingStatus();

        $this->transactionRepository->save($this->transactionEntity);

        return $this->transactionEntity;
    }

    protected function setPayerOnTransaction(int $payerId): self
    {
        $payer = $this->userRepository->getById($payerId);

        if (!isset($payer)) {
            throw new TransactionServiceException(
                'O usuário pagador informado não existe.',
                Response::HTTP_NOT_FOUND
            );
        }

        $this->transactionEntity->setPayer($payer);

        return $this;
    }

    protected function setPayeeOnTransaction(int $payeeId): self
    {
        $payee = $this->userRepository->getById($payeeId);

        if (!isset($payee)) {
            throw new TransactionServiceException(
                'O usuário a ser pago informado não existe.',
                Response::HTTP_NOT_FOUND
            );
        }

        $this->transactionEntity->setPayee($payee);

        return $this;
    }

    protected function notifyPayee(TransactionEntityInterface $transaction): self
    {

        $payer = $transaction->getPayer();
        $payee = $transaction->getPayee();

        $amount = number_format($transaction->getAmount(), 2, ',', '.');

        $this->notificationEntity
            ->setUser($payee)
            ->setTitle('Você recebeu uma transferência!')
            ->setMessage(
                sprintf('%s acabou de te transferir R$%s', $payer->getFirstName(), $amount)
            );

        $this->notificationService->notify($this->notificationEntity);

        return $this;
    }

}
