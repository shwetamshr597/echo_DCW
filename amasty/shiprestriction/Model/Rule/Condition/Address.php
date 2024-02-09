<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Shipping Restrictions for Magento 2
*/

namespace Amasty\Shiprestriction\Model\Rule\Condition;

/**
 * Class Address
 */
class Address extends \Amasty\CommonRules\Model\Rule\Condition\Address
{
    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function loadAttributeOptions()
    {
        parent::loadAttributeOptions();

        $attributes = $this->getAttributeOption();
        unset($attributes['shipping_method']);
        $this->setAttributeOption($attributes);

        return $this;
    }
}
