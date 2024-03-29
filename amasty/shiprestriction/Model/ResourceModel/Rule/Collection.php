<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Shipping Restrictions for Magento 2
*/

namespace Amasty\Shiprestriction\Model\ResourceModel\Rule;

/**
 * Class Collection
 */
class Collection extends \Amasty\CommonRules\Model\ResourceModel\Rule\Collection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Amasty\Shiprestriction\Model\Rule::class,
            \Amasty\Shiprestriction\Model\ResourceModel\Rule::class
        );
    }
}
