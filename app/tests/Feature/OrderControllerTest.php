<?php
namespace Tests\Feature;

use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    //測試 name 欄位包含非英文字符
    public function testNameContainsNonEnglishCharacters()
    {
        $payload = [
            'id' => 'A0000001',
            'name' => 'Elody Holiday Inn #',  //非英文字
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => '2000',
            'currency' => 'TWD',
        ];

        $response = $this->postJson('/orders', $payload); 
        $response->assertStatus(400)
                 ->assertJson([
                     'errors' => [
                        '400 - Name contains non-English characters',
                     ]
                 ]);
    }

     //測試 name 欄位英文但第一個字母不是大寫
     
    public function testNameNotCapitalized()
    {
        $payload = [
            'id' => 'A0000002',
            'name' => 'elody holiday inn',  //第一個字母是小寫
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => '2000',
            'currency' => 'TWD',
        ];

        $response = $this->postJson('/orders', $payload);  
        $response->assertStatus(400)
                 ->assertJson([
                     'errors' =>[ 
                        '400 - Name is not capitalized.',
                     ]
                 ]);
    }

    
     //測試price超過2000
     
    public function testPriceExceedsLimit()
    {
        $payload = [
            'id' => 'A0000003',
            'name' => 'Elody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => '2500',  //超過2000
            'currency' => 'TWD',
        ];

        $response = $this->postJson('/orders', $payload); 
        $response->assertStatus(400)
                 ->assertJson([
                     'errors' => [
                        '400 - Price exceeds the allowed maximum value of 2000.',
                     ]
                 ]);
    }

    
    //測試currency不是TWD或USD
     
    public function testInvalidCurrency()
    {
        $payload = [
            'id' => 'A0000004',
            'name' => 'Elody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => '2000',
            'currency' => 'EUR',  //無效的貨幣
        ];

        $response = $this->postJson('/orders', $payload);  
        $response->assertStatus(400)
                 ->assertJson([
                     'errors' => [
                        '400 - Invalid currency format. Supported formats are TWD and USD.',
                     ]
                 ]);
    }

    
    //測試資料合法情況
     
    public function testValidOrder()
    {
        $payload = [
            'id' => 'A0000005',
            'name' => 'Elody Holiday Inn',
            'address' => [
                'city' => 'taipei-city',
                'district' => 'da-an-district',
                'street' => 'fuxing-south-road',
            ],
            'price' => '2000',  
            'currency' => 'TWD',  
        ];

        $response = $this->postJson('/orders', $payload);  
        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Order processed successfully!',
                 ]);
    }
}
