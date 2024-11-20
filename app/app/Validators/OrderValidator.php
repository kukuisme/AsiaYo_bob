<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class OrderValidator
{
    public function validate(array $data)
    {
        //定義驗證規則
        $rules = [
            // 'name' => ['required', 'regex:/^[A-Z][a-z]*(?: [A-Z][a-z]*)*$/'],
            'name' => ['required', 
            'regex:/^[A-Za-z\s]*$/', // 檢查是否只包含字母與空格
            function ($attribute, $value, $fail) {
                // 檢查第一個字母是否為大寫
                if (strlen($value) > 0 && ctype_lower($value[0])) {
                    $fail('400 - Name is not capitalized.');
                }
            }],
            'price' => ['required', 'numeric', 'max:2000'],
            'currency' => ['required', 'in:TWD,USD'],
            'address.city' => ['required', 'string'],
            'address.district' => ['required', 'string'],
            'address.street' => ['required', 'string'],
        ];

        //定義錯誤訊息
        $messages = [
            'name.regex' => '400 - Name contains non-English characters',
            'price.max' => '400 - Price exceeds the allowed maximum value of 2000.',
            'currency.in' => '400 - Invalid currency format. Supported formats are TWD and USD.',
        ];

        //驗證
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return [
                'status' => 'error',
                'errors' => $validator->errors()->all()
            ];
        }

        return ['status' => 'success'];
    }
}
