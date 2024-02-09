<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Shipping Restrictions for Magento 2
*/

namespace Amasty\Shiprestriction\Controller\Adminhtml\Rule;

/**
 * Delete action
 */
class Delete extends \Amasty\CommonRules\Controller\Adminhtml\Rule\AbstractDelete
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Amasty_Shiprestriction::rule';
}
