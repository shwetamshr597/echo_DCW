<?php

namespace Ecommerce121\RestrictCompanyCRUD\Plugin\Block\Company;

use Magento\Company\Block\Company\CompanyProfile;

class RemoveCompanyProfileEdit
{
    /**
     * Remove Company profile option to edit company
     *
     * @param CompanyProfile $subject
     * @param bool $result
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterIsEditLinkDisplayed(CompanyProfile $subject, bool $result): bool
    {
        return false;
    }
}
