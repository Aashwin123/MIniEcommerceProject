<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderRepository implements OrderRepositoryInterface
{
    protected $model;

    public function __construct(Order $order)
    {
        $this->model = $order;
    }

    public function all()
    {
        return $this->model->with('items')->get();
    }

    public function find($id)
    {
        return $this->model->with('items')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $order = $this->find($id);
        $order->update($data);
        return $order;
    }

    public function delete($id)
    {
        $order = $this->find($id);
        return $order->delete();
    }

    public function createWithItems(array $orderData, array $items)
{
    $order = $this->model->create($orderData);

    $total = 0;

    foreach ($items as $item) {
        $product = Product::findOrFail($item['product_id']); // fetch price from products

        $order->items()->create([
            'product_id' => $product->id,
            'quantity'   => $item['quantity'],
            'price'      => $product->price, // ✅ storing price at time of order
        ]);

        $total += $product->price * $item['quantity'];
    }

    $order->update(['total' => $total]);

    return $order->load('items.product');
}

     public function createorder(array $data)
    {
        return Order::create($data);
    }

    public function getOrdersByUser($userId)
{
    return $this->model->with('items')
                       ->where('user_id', $userId)
                       ->get();
}



public function getAllOrdersWithUser()
{
    return $this->model->with(['items', 'user:id,name,email']) // eager load items + user
                       ->get();
}


   


}
