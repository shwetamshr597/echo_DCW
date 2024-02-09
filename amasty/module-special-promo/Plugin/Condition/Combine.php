<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Special Promotions Base for Magento 2
 */

namespace Amasty\Rules\Plugin\Condition;

use Amasty\Rules\Api\ExtendedValidatorInterface;

/**
 * Additional validation for rules with buyxget actions,
 */
class Combine
{
    /**
     * @var ExtendedValidatorInterface
     */
    private $validator;

    public function __construct(
        ExtendedValidatorInterface $validator
    ) {
        $this->validator = $validator;
    }

    public function aroundValidate(
        \Magento\Rule\Model\Condition\Combine $subject,
        \Closure $proceed,
        $type
    ) {
        $validationResult = $this->validator->validate($subject, $type);
        if ($validationResult !== null) {
            return $validationResult;
        }

        return $proceed($type);
    }
}
