<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    public function store(Request $request)
    {
        //調用Service處理訂單邏輯
        $result = $this->orderService->processOrder($request->all());
        
        //根據結果給適當的回應
        if ($result['status'] === 'error') 
        {
            return response()->json([
                'errors' => $result['errors']
            ], 400);
        }

        return response()->json([
            'message' => 'Order processed successfully!',
            'data' => $result['data']
        ], 201);
    }
}
