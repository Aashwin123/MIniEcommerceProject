<?php

namespace App\Repositories\Contracts;




interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    // You can add product-specific methods here if needed
   public function createWithItems(array $orderData, array $items);

   public function getOrdersByUser($userId);

   public function getAllOrdersWithUser();


}
