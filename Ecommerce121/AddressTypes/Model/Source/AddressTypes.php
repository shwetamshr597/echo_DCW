<?php

declare(strict_types=1);

namespace Ecommerce121\AddressTypes\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class AddressTypes extends AbstractSource
{
    /**
     * Get all AddressTypes options
     *
     * @return array<mixed>
     */
    public function getAllOptions(): array
    {
        if (!$this->_options) {
            $this->_options = [
                ['value' => 'shipping', 'label' => __('Shipping')],
                ['value' => 'billing', 'label' => __('Billing')]
            ];
        }

        return $this->_options;
    }
}
