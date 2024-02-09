<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Shipping Restrictions for Magento 2
*/
declare(strict_types=1);

namespace Amasty\Shiprestriction\Model;

class CanShowMessageOnce
{
    public function execute(Rule $rule, string $carrierCode): bool
    {
        if (!$rule->getShowRestrictionMessageOnce()) {
            return false;
        }

        $carriers = $rule->getData('carriers');
        if (!$carriers) {
            return false;
        }

        $carrierCodes = explode(',', $rule->getData('carriers'));
        return in_array($carrierCode, $carrierCodes);
    }
}
