<?php

declare(strict_types=1);

namespace Ecommerce121\RestrictCompanyCRUD\Plugin;

use Magento\Company\Controller\Profile\Edit;

class DisableProfileEditAccess
{
    /**
     * Prevent customer from accessing company profile edit page
     *
     * @param Edit $subject
     * @param bool $result
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterIsAllowed(Edit $subject, bool $result): bool
    {
        return false;
    }
}
