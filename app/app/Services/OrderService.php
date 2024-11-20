<?php

namespace App\Services;

use App\Validators\OrderValidator;

class OrderService
{
    protected $validator;

    public function __construct(OrderValidator $validator)
    {
        $this->validator = $validator;
    }

    public function processOrder(array $data)
    {
        //驗證訂單數據
        $validationResult = $this->validator->validate($data);

        if ($validationResult['status'] === 'error') {
            return $validationResult;
        }

        //處理匯率轉換
        if ($data['currency'] === 'USD') {
            try {
                $data['price'] = $data['price'] * 31;
                $data['currency'] = 'TWD';
            } catch (\Exception $e) {
                return [
                    'status' => 'error',
                    'errors' => ['400 - Currency converted to TWD, but an error occurred during price adjustment.']
                ];
            }
        }

        return [
            'status' => 'success',
            'data' => $data
        ];
    }
}
