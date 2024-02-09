<?php

declare(strict_types=1);

namespace Ecommerce121\NonEditableCustomerAddress\Plugin\Sales\Block\Adminhtml\Order\Create\Customer;

use Magento\Sales\Block\Adminhtml\Order\Create\Customer;

class RemoveCreateNewCustomerButton
{
    /**
     * Remove "Create new customer" button in admin order creation
     *
     * @param Customer $subject
     * @param string $result
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetButtonsHtml(Customer $subject, string $result): string
    {
        return '';
    }
}
