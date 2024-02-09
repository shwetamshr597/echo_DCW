<?php

declare(strict_types=1);

namespace Ecommerce121\ChangeMandatoryFields\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Customer\Setup\CustomerSetupFactory;

class CustomerPhoneAttributeNotRequired implements DataPatchInterface
{
    /**
     * CustomerPhoneAttributeNotRequired constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly CustomerSetupFactory $customerSetupFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies() : array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases() : array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function apply() : CustomerPhoneAttributeNotRequired
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $customerSetup->updateAttribute(
            'customer_address',
            'telephone',
            'is_required',
            0
        );
        return $this;
    }
}
