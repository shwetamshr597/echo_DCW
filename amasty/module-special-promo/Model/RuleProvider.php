<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Special Promotions Base for Magento 2
 */

namespace Amasty\Rules\Model;

use Amasty\Rules\Api\RuleProviderInterface;
use Amasty\Rules\Model\ResourceModel\Rule as RuleResource;
use Amasty\Rules\Api\Data\RuleInterface;

class RuleProvider implements RuleProviderInterface
{
    /**
     * @var RuleResource
     */
    private $ruleResource;

    /**
     * @var RuleFactory
     */
    private $ruleFactory;

    /**
     * @var array [SalesRule_id => SpecialPromotions_Rule]
     */
    private $storage = [];

    public function __construct(
        RuleResource $ruleResource,
        RuleFactory $ruleFactory
    ) {
        $this->ruleResource = $ruleResource;
        $this->ruleFactory = $ruleFactory;
    }

    /**
     * @param int $ruleId
     * @return \Amasty\Rules\Model\Rule
     */
    public function getAmruleByRuleId($ruleId)
    {
        if (!isset($this->storage[$ruleId])) {
            $rule = $this->ruleFactory->create();
            $this->ruleResource->load($rule, $ruleId, RuleInterface::KEY_SALESRULE_ID);
            $this->storage[$ruleId] = $rule;
        }

        return $this->storage[$ruleId];
    }
}
