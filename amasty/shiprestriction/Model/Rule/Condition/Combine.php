<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Shipping Restrictions for Magento 2
*/

namespace Amasty\Shiprestriction\Model\Rule\Condition;

class Combine extends \Amasty\CommonRules\Model\Rule\Condition\Combine
{
    public const AMASTY_SHIP_RESTRICTION_PATH_TO_CONDITIONS = 'Amasty\Shiprestriction\Model\Rule\Condition\\';

    /**
     * @var string
     */
    protected $conditionsAddressPath = self::AMASTY_SHIP_RESTRICTION_PATH_TO_CONDITIONS .'Address';

    /**
     * Combine constructor.
     * @param \Magento\Rule\Model\Condition\Context $context
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param Address $conditionAddress
     * @param \Amasty\CommonRules\Model\Rule\Factory\HandleFactory $handleFactory
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param \Amasty\CommonRules\Model\Rule\Factory\CombineHandleFactory $combineHandleFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        Address $conditionAddress,
        \Amasty\CommonRules\Model\Rule\Factory\HandleFactory $handleFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        \Amasty\CommonRules\Model\Rule\Factory\CombineHandleFactory $combineHandleFactory,
        array $data = []
    ) {
        $this->_conditionAddress = $conditionAddress;
        $this->setType(self::AMASTY_SHIP_RESTRICTION_PATH_TO_CONDITIONS . 'Combine');

        parent::__construct(
            $context,
            $eventManager,
            $conditionAddress,
            $handleFactory,
            $moduleManager,
            $combineHandleFactory,
            $data
        );
    }
}
