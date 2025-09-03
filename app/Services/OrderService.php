<?php

namespace App\Services;

use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderService
{
    protected $repo;

    public function __construct(OrderRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function listOrders()
    {
        return $this->repo->all();
    }

    public function getOrder($id)
    {
        return $this->repo->find($id);
    }

    public function createOrder(array $data)
    {
        return $this->repo->create($data);
    }

    public function updateOrder($id, array $data)
    {
        return $this->repo->update($id, $data);
    }

    public function deleteOrder($id)
    {
        return $this->repo->delete($id);
    }

    public function createOrderWithItems(array $data, array $items)
    {
        return $this->repo->createWithItems($data, $items);
    }

    public function listUserOrders($userId)
{
    return $this->repo->getOrdersByUser($userId);
}



public function listAllOrdersWithUser()
{
    return $this->repo->getAllOrdersWithUser();
}


}
