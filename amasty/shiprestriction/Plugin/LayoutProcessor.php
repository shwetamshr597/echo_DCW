<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Shipping Restrictions for Magento 2
*/

namespace Amasty\Shiprestriction\Plugin;

/**
 * phpcs:ignoreFile
 */
class LayoutProcessor
{
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        $result
    ) {
        $result['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['price']['template'] = 'Amasty_Shiprestriction/tax-price';

        return $result;
    }
}
