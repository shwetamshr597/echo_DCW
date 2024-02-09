<?php

namespace DCW\OrderEditApi\Model\Api;

use Psr\Log\LoggerInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
class OrderEdit
{
  protected $logger;

    /**
     * @var OrderItemRepositoryInterface
     */
    private $orderItemRepository;
    
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;


    public function __construct(
        LoggerInterface $logger,
        OrderItemRepositoryInterface $orderItemRepository,
        OrderRepositoryInterface $orderRepository
    )
    {
        $this->orderItemRepository = $orderItemRepository;
        $this->orderRepository = $orderRepository;
        $this->logger = $logger;
    }

  /**
  * @inheritdoc
  */

  public function deleteOrderItem($itemId,$orderId)
  {
    $response = ['success' => false];
    try {
        $order=$this->orderRepository->get($orderId); 
        $items = $order->getAllVisibleItems();
        $NumberOfItem = count($items);
        if($NumberOfItem>0) {
            $this->orderItemRepository->deleteById($itemId);
            $response = ['success' => true, 'message' => $itemId];
        } else {
          $response = ['success' => false, 'message' => $e->getMessage()];
        }
    } catch (\Exception $e) {
      $response = ['success' => false, 'message' => $e->getMessage()];
      $this->logger->info($e->getMessage());
    }
    $returnArray = json_encode($response);
    return $returnArray;
  }
}