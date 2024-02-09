<?php

namespace DCW\OrderEditApi\Api;

interface OrderEditInterface
{
  /**
  * GET for Post api
  * @param string $itemId
  * @param string $orderId
  * @return string
  */

  public function deleteOrderItem($itemId,$orderId);
}