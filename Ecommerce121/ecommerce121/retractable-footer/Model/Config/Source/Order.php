<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommerce121\FixedFooter\Model\Config\Source;

use Ecommerce121\FixedFooter\Model\Config;

/**
 * @api
 * @since 100.0.2
 */
class Order implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $rateList = [];
        foreach (Config::$_ratesOrder as $key => $label) {
            $rateList[] = [
                'value' => $key,
                'label' => __($label),
            ];
        }
        return $rateList;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return Config::$_ratesOrder;
    }
}
