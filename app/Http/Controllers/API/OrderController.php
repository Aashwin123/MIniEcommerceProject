<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    


      





     public function index()
{
    try {
        $orders = $this->service->listOrders();
        
        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully',
            'data' => $orders
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve orders',
            'error' => $e->getMessage()
        ], 500);
    }
}



    public function show($id)
    {
        return response()->json($this->service->getOrder($id));
    }

    


public function store(Request $request)
{
    try {
        $data = $request->validate([
            'total' => 'required|numeric',
            'status' => 'sometimes|string',
        ]);

        $data['user_id'] = $request->user()->id;
        $order = $this->service->createOrder($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Order successfully created',
            'data' => $order
        ], 201);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create order',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'total' => 'sometimes|numeric',
            'status' => 'sometimes|string',
        ]);

        $res=$this->service->updateOrder($id, $data);
        if($res){
        return response()->json('updated successfull');

        }
        
    }

    public function destroy($id)
    {
        $this->service->deleteOrder($id);
        return response()->json(['message' => 'Order deleted successfully']);
    }

   public function storeWithItems(Request $request)
{
    try {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $orderData = [
            'user_id' => $request->user()->id,
            'total' => 0, // will be recalculated from product prices
            'status' => 'pending',
        ];

        $order = $this->service->createOrderWithItems($orderData, $data['items']);

        return response()->json([
            'success' => true,
            'message' => 'Order with items created successfully',
            'data' => $order,
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to create order with items',
            'error' => $e->getMessage()
        ], 500);
    }
}



public function myOrders(Request $request)
{
    try {
        $userId = $request->user()->id;

        $orders = $this->service->listUserOrders($userId);

        return response()->json([
            'success' => true,
            'message' => 'Your order history retrieved successfully',
            'data' => $orders
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve order history',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function adminOrders()
{
    try {
        $orders = $this->service->listAllOrdersWithUser();

        return response()->json([
            'success' => true,
            'message' => 'All orders retrieved successfully',
            'data' => $orders
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve orders',
            'error' => $e->getMessage()
        ], 500);
    }
}


}



    

    
   

