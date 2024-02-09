<?php

declare(strict_types=1);

namespace Ecommerce121\CompanyDefaultWarehouse\Model\Company\Source;

use Magento\Company\Api\Data\CompanyInterface;
use Magento\Framework\Api\ExtensionAttributesFactory;

class WarehouseExtensionAttributeLoader
{
    /**
     * WarehouseExtensionAttributeLoader constructor
     *
     * @param ExtensionAttributesFactory $extAttrFactory
     */
    public function __construct(private readonly ExtensionAttributesFactory $extAttrFactory)
    {
    }

    /**
     * Initialize extension attributes if needed and set the 'default_warehouse_id' value
     *
     * @param CompanyInterface $company
     * @return void
     */
    public function execute(CompanyInterface $company): void
    {
        $defaultWarehouseId = $company->getData('default_warehouse_id'); // @phpstan-ignore-line

        $extensionAttributes = $company->getExtensionAttributes();

        if ($extensionAttributes === null) {
            $extensionAttributes = $this->extAttrFactory->create(CompanyInterface::class);
            /** @noinspection PhpParamsInspection */
            $company->setExtensionAttributes($extensionAttributes); // @phpstan-ignore-line
        }

        $extensionAttributes->setDefaultWarehouseId($defaultWarehouseId ?? ''); // @phpstan-ignore-line
    }
}
