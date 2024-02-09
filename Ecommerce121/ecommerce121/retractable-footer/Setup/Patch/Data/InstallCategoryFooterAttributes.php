<?php
/**
 * Copyright (c) 2021 121Ecommerce (https://www.121ecommerce.com/)
 */
declare(strict_types=1);

namespace Ecommerce121\FixedFooter\Setup\Patch\Data;

use Ecommerce121\FixedFooter\Model\Config\Backend\Custom;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Category\Attribute\Backend\Image;
use Magento\Catalog\Setup\CategorySetup;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class InstallCategoryFooterAttributes implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * PatchInitial constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CategorySetupFactory $categorySetupFactory
     * @param DefaultCategoryFactory $defaultCategoryFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categorySetupFactory = $categorySetupFactory;
    }

    /**
     * Add category_display_in_footer and category_footer_icon category attribute
     * if they don't exist
     */
    public function apply()
    {
        /** @var CategorySetup $categorySetup */
        $categorySetup = $this->categorySetupFactory->create(['setup' => $this->moduleDataSetup]);

        if (!$categorySetup->getAttribute(Category::ENTITY, 'category_display_in_footer')) {
            $categorySetup->addAttribute(
                Category::ENTITY,
                'category_display_in_footer',
                [
                    'type' => 'int',
                    'label' => 'Display In Footer',
                    'input' => 'select',
                    'visible' => true,
                    'default' => '0',
                    'backend' => Custom::class,
                    'sort_order' => 1,
                    'required' => false,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'General Information',
                ]
            );
        }

        if (!$categorySetup->getAttribute(Category::ENTITY, 'category_footer_icon')) {
            $categorySetup->addAttribute(
                Category::ENTITY,
                'category_footer_icon',
                [
                    'type' => 'varchar',
                    'label' => 'Footer icon',
                    'input' => 'image',
                    'backend' => Image::class,
                    'visible' => true,
                    'sort_order' => 9,
                    'required' => false,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'General Information',
                ]
            );
        }
    }

    /**
     * @return array
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * This version associate patch with Magento setup version.
     */
    public static function getVersion()
    {
        return '1.0.2';
    }

    /**
     * Get aliases (previous names) for the patch.
     */
    public function getAliases()
    {
        return [];
    }
}
