<?php

declare(strict_types=1);

namespace Ecommerce121\CompanyDefaultWarehouse\Plugin\CompanyRepository;

use Ecommerce121\CompanyDefaultWarehouse\Model\Company\Source\WarehouseExtensionAttributeLoader;
use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Company\Api\Data\CompanyInterface;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class LoadDefaultWarehouseOnGetPlugin
{
    /**
     * LoadDefaultWarehouseOnGetPlugin constructor
     * @param WarehouseExtensionAttributeLoader $warehouseExtAttrLoader
     */
    public function __construct(
        private readonly WarehouseExtensionAttributeLoader $warehouseExtAttrLoader
    ) {
    }

    /**
     * Enrich the given Company object with the default_warehouse_id attribute
     *
     * @param CompanyRepositoryInterface $subject
     * @param CompanyInterface $company
     * @return CompanyInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(
        CompanyRepositoryInterface $subject,
        CompanyInterface $company
    ): CompanyInterface {
        $this->warehouseExtAttrLoader->execute($company);

        return $company;
    }
}
