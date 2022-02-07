<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Exceptions\RepositoryException;
use App\Exceptions\EntityException;
use App\Exceptions\RequestException;
use App\Exceptions\ServiceException;
use App\Interfaces\Transaction\TransactionRequestInterface;
use App\Interfaces\Transaction\TransactionServiceInterface;

class TransactionController extends Controller
{

    /**
     * Performs a transaction between two users
     *
     * @param TransactionRequestInterface $transactionRequest
     * @param TransactionServiceInterface $transactionService
     * @return JsonResponse
     * @throws RepositoryException
     * @throws EntityException
     * @throws RequestException
     * @throws ServiceException
     */
    public function transaction(
        TransactionRequestInterface $transactionRequest,
        TransactionServiceInterface $transactionService
    ): JsonResponse
    {
        try {

            $request = $transactionRequest->validate();

            return response()->json([
                'data' => $transactionService->transact(
                    $request->input('payer_id'),
                    $request->input('payee_id'),
                    $request->input('amount')
                ),
                'message' => 'Transação realizada com sucesso!'
            ], Response::HTTP_CREATED);

        } catch (RequestException $e) {
            return response()->json([
                'message' => 'É necessário validar os dados da transação.',
                'errors' => json_decode($e->getMessage())
            ], $e->getCode());

        } catch (EntityException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);

        } catch (ServiceException | RepositoryException $e) {
            return response()->json(
                [
                    'message' => $e->getMessage()
                ],
                $e->getCode() > 0 ? $e->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR
            );

        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'Não foi possível realizar a transação no momento.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
