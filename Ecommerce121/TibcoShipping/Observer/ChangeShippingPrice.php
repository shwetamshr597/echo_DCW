<?php

declare(strict_types=1);

namespace Ecommerce121\TibcoShipping\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Setup\Exception;
use Ecommerce121\ERPConnector\Helper\TibcoShipping;

class ChangeShippingPrice implements ObserverInterface
{
    private const SUCCESS = 200;
    /**
     * @var FreightCalculation
     */

    public function __construct(
        TibcoShipping $tibcoShipping
    ) {
        $this->_tibcoShipping = $tibcoShipping;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @throws Exception
     */

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            /** @var \Magento\Sales\Model\Order $order */
            $order = $observer->getData('order');
            if ($order->getState() == "new") {
                $newShippingPrice = $this->_tibcoShipping->getFreightCalculation();

                $oldPrice = $order->getShippingAmount();
                $order->setShippingAmount($newShippingPrice['price']);
                $order->setBaseShippingAmount($newShippingPrice['price']);

                $order->setGrandTotal(($order->getGrandTotal() - $oldPrice) + $newShippingPrice['price']);
                $order->setBaseGrandTotal(($order->getBaseGrandTotal() - $oldPrice) + $newShippingPrice['price']);
            }
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
