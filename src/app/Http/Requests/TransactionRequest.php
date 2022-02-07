<?php

namespace App\Http\Requests;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\Transaction\TransactionRequestException;
use App\Interfaces\Transaction\TransactionRequestInterface;

class TransactionRequest implements TransactionRequestInterface
{

    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Validates the request and returns the instance if valid
     *
     * @throws TransactionRequestException
     * @return Request
     */
    public function validate(): Request
    {
        $validator = Validator::make($this->request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            throw new TransactionRequestException(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->request;
    }

    /**
     * Returns the validation rules
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'payer_id' => 'required|integer|different:payee_id',
            'payee_id' => 'required|integer|different:payer_id',
            'amount' => 'required|numeric|gt:0'
        ];
    }

    /**
     * Returns the validation messages
     *
     * @return array
     */
    protected function messages(): array
    {
        return [
            'payer_id.required' => 'É necessário identificar o usuário pagador.',
            'payer_id.integer' => 'É necessário identificar o usuário pagador por meio de seu código.',
            'payer_id.different' => 'O usuário pagador precisa ser diferente do usuário a ser pago.',

            'payee_id.required' => 'É necessário identificar o usuário a ser pago.',
            'payee_id.integer' => 'É necessário identificar o usuário a ser pago por meio de seu código.',
            'payee_id.different' => 'O usuário a ser pago precisa ser diferente do usuário pagador.',

            'amount.required' => 'É necessário informar o valor a ser pago.',
            'amount.numeric' => 'É necessário informar um valor válido para realizar a transação.',
            'amount.gt' => 'É necessário que o valor informado seja maior que 0 (zero).'
        ];
    }

}
