<?php

declare(strict_types=1);

namespace Ecommerce121\AddressTypes\Setup\Patch\Data;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddAddressTypesCustomerAttribute implements DataPatchInterface
{
    private const CUSTOMER_ADDRESS_ENTITY_TYPE_ID = 'customer_address';
    private const ADDRESS_TYPES_ATTRIBUTE_CODE = 'address_type';

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly CustomerSetupFactory     $customerSetupFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function apply(): AddAddressTypesCustomerAttribute
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $customerSetup->addAttribute(
            self::CUSTOMER_ADDRESS_ENTITY_TYPE_ID,
            self::ADDRESS_TYPES_ATTRIBUTE_CODE,
            [
                'type' => 'static',
                'label' => 'Address Type',
                'input' => 'text',
                'required' => false,
                'sort_order' => 1000,
                'visible' => true,
                'system' => false,
                'is_user_defined' => true,
                'is_visible_in_grid' => true,
                'is_used_in_grid' => true,
                'is_filterable_in_grid' => true,
            ]
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }
}
