<?php

declare(strict_types=1);

namespace Ecommerce121\AddressTypes\Plugin\Sales\Block\Adminhtml\Order\Create\Shipping\Address;

use Magento\Sales\Block\Adminhtml\Order\Create\Shipping\Address;

class ShippingNotSameAsBilling
{
    /**
     * Ensure shipping address is NOT the same as billing address
     *
     * @param Address $subject
     * @param bool $result
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetIsAsBilling(Address $subject, bool $result): bool
    {
        return false;
    }
}
