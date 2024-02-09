<?php

declare(strict_types=1);

namespace Ecommerce121\EchoIds\Setup\Patch\Data;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class EchoLocIdAddressAttribute implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @var CustomerSetupFactory
     */
    private CustomerSetupFactory $customerSetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
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
    public function apply() : EchoLocIdAddressAttribute
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $customerSetup->addAttribute(
            'customer_address',
            'echo_loc_id',
            [
                'type' => 'static',
                'label' => 'Echo Loc Id',
                'input' => 'text',
                'required' => false,
                'sort_order' => 870,
                'visible' => false,
                'system' => false,
                'is_user_defined' => true
            ]
        );

        return $this;
    }
}
