<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Special Promotions Base for Magento 2
 */

namespace Amasty\Rules\Plugin\SalesRuleStaging\Model\Rule;

/**
 * ee21 compatibility (fix magento fatal)
 */
class HydratorPlugin
{
    /**
     * @param \Magento\SalesRuleStaging\Model\Rule\Hydrator $subject
     * @param array $data
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeHydrate($subject, array $data)
    {
        if (isset($data['rule'])) {
            if (isset($data['rule']['conditions'])) {
                $data['conditions'] = $data['rule']['conditions'];
            }

            if (isset($data['rule']['actions'])) {
                $data['actions'] = $data['rule']['actions'];
            }

            unset($data['rule']);
        }

        return [$data];
    }
}
