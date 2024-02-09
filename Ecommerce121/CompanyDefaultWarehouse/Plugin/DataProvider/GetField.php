<?php

declare(strict_types=1);

namespace Ecommerce121\CompanyDefaultWarehouse\Plugin\DataProvider;

use Magento\Company\Api\Data\CompanyInterface;
use Magento\Company\Model\Company;
use Magento\Company\Model\Company\DataProvider;

class GetField
{
    /**
     * Add 'default_warehouse_id' value to Company general data
     *
     * @param DataProvider $subject
     * @param array<mixed> $result
     * @param CompanyInterface $company
     * @return array<mixed>
     * @SuppressWarnings(UnusedFormalParameter)
     */
    public function afterGetGeneralData(DataProvider $subject, array $result, CompanyInterface $company): array
    {
        /** @var Company $company */
        $echoFields = [
            'default_warehouse_id' => $company->getData('default_warehouse_id')
        ];

        return array_merge($result, $echoFields);
    }
}
