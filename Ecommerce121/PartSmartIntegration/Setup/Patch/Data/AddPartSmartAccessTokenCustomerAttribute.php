<?php

declare(strict_types=1);

namespace Ecommerce121\PartSmartIntegration\Setup\Patch\Data;

use Ecommerce121\PartSmartIntegration\Constants;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Attribute;
use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Zend_Validate_Exception;

class AddPartSmartAccessTokenCustomerAttribute implements DataPatchInterface
{
    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param Config $eavConfig
     * @param Attribute $attributeResource
     */
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly EavSetupFactory $eavSetupFactory,
        private readonly Config $eavConfig,
        private readonly Attribute $attributeResource
    )
    {}

    /**
     * @return $this
     * @throws LocalizedException
     * @throws Zend_Validate_Exception
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            Customer::ENTITY,
            Constants::EAV_CUSTOMER_ACCESS_TOKEN,
            [
                'input' => 'text',
                'is_visible_in_grid' => false,
                'visible' => true,
                'user_defined' => true,
                'is_filterable_in_grid' => false,
                'system' => false,
                'label' => 'Part Smart Access Token',
                'type' => 'text',
                'is_used_in_grid' => false,
                'required' => false,
                'position' => 120
            ]
        );

        $eavSetup->addAttributeToSet(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
            'Default',
            Constants::EAV_CUSTOMER_ACCESS_TOKEN
        );

        $attribute = $this->eavConfig->getAttribute(
            Customer::ENTITY,
            Constants::EAV_CUSTOMER_ACCESS_TOKEN
        );
        $attribute->setData(
            'used_in_forms',
            ['adminhtml_customer', 'adminhtml_checkout']
        );
        $this->attributeResource->save($attribute);



        return $this;
    }

    /**
     * @return string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getAliases(): array
    {
        return [];
    }
}
