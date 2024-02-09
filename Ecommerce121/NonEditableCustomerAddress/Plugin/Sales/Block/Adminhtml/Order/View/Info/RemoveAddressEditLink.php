<?php

declare(strict_types=1);

namespace Ecommerce121\NonEditableCustomerAddress\Plugin\Sales\Block\Adminhtml\Order\View\Info;

use Magento\Sales\Block\Adminhtml\Order\View\Info;
use Magento\Sales\Model\Order\Address;

class RemoveAddressEditLink
{
    /**
     * Remove "edit" link from addresses in admin order view
     *
     * @param Info $subject
     * @param string $result
     * @param Address $address
     * @param string $label
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetAddressEditLink(Info $subject, string $result, Address $address, string $label = ''): string
    {
        return '';
    }
}
