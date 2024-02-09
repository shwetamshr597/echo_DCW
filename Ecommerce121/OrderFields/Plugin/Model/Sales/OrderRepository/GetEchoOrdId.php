<?php

declare(strict_types=1);

namespace Ecommerce121\OrderFields\Plugin\Model\Sales\OrderRepository;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\OrderRepository;

class GetEchoOrdId
{
    /**
     * After get plugin
     *
     * @param OrderRepository $subject
     * @param OrderInterface $result
     * @param int $id
     * @return OrderInterface
     * @SuppressWarnings(ShortVariable)
     * @SuppressWarnings(UnusedFormalParameter)
     */
    public function afterGet(OrderRepository $subject, OrderInterface $result, int $id): OrderInterface
    {
        // @phpstan-ignore-next-line
        $echoOrdId = $result->getEchoOrdId();
        $extensionAttributes = $result->getExtensionAttributes();
        // @phpstan-ignore-next-line
        $extensionAttributes->setEchoOrdId($echoOrdId);
        // @phpstan-ignore-next-line
        $result->setExtensionAttributes($extensionAttributes);
        return $result;
    }
}
