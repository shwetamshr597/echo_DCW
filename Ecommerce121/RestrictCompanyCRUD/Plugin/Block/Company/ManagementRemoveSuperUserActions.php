<?php

namespace Ecommerce121\RestrictCompanyCRUD\Plugin\Block\Company;

use Magento\Company\Block\Company\Management;

class ManagementRemoveSuperUserActions
{
    /**
     * Disable Super user actions
     *
     * @param Management $subject
     * @param bool $result
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterIsSuperUser(Management $subject, bool $result): bool
    {
        return false;
    }
}
