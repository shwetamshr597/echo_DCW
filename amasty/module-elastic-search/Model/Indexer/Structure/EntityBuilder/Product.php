<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Elastic Search Base for Magento 2
 */

namespace Amasty\ElasticSearch\Model\Indexer\Structure\EntityBuilder;

use Amasty\ElasticSearch\Api\Data\Indexer\Structure\EntityBuilderInterface;
use Amasty\ElasticSearch\Model\Config as ConfigProvider;
use Amasty\ElasticSearch\Model\Indexer\Structure\AnalyzerBuilder\DefaultBuilder;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Customer\Model\ResourceModel\Group\CollectionFactory;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

class Product implements EntityBuilderInterface
{
    public const ATTRIBUTE_TYPE_TEXT       = 'text';
    public const ATTRIBUTE_TYPE_KEYWORD    = 'keyword';
    public const ATTRIBUTE_TYPE_FLOAT      = 'float';
    public const ATTRIBUTE_TYPE_INT        = 'integer';
    public const ATTRIBUTE_TYPE_DATE       = 'date';
    public const DEFAULT_ATTRIBUTE_CODE_ARRAY = ['category_ids', 'visibility'];

    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @var array
     */
    private $customerGroupIds;

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    public function __construct(
        Config $eavConfig,
        ConfigProvider $configProvider,
        CollectionFactory $customerGroupCollectionFactory
    ) {
        $this->eavConfig = $eavConfig;
        $this->configProvider = $configProvider;
        $customerGroupCollection = $customerGroupCollectionFactory->create();
        $this->customerGroupIds = $customerGroupCollection->getAllIds();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function buildEntityFields()
    {
        $attributeCodes = $this->eavConfig->getEntityAttributeCodes(ProductAttributeInterface::ENTITY_TYPE_CODE);
        $allAttributes = [];
        $useCustomAnalyzer = $this->configProvider->useCustomAnalyzer();

        foreach ($attributeCodes as $attributeCode) {
            $attribute = $this->eavConfig->getAttribute(ProductAttributeInterface::ENTITY_TYPE_CODE, $attributeCode);
            $attributeType = $this->getAttributeType($attribute);
            $allAttributes[$attributeCode] = ['type' => $attributeType];

            if ($attributeCode == "category_ids") {
                $allAttributes[$attributeCode] = [
                    'type' => self::ATTRIBUTE_TYPE_INT
                ];
            } elseif ($attributeCode == "sku") {
                $allAttributes[$attributeCode]['fielddata'] = true;
            }

            if ($allAttributes[$attributeCode]['type'] == self::ATTRIBUTE_TYPE_DATE) {
                $allAttributes[$attributeCode]['format'] = 'yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis';
            } elseif ($attributeCode == "price") {
                foreach ($this->customerGroupIds as $groupId) {
                    $allAttributes[$attributeCode . '_' . $groupId] = ['type' => self::ATTRIBUTE_TYPE_FLOAT];
                }
            }

            if ($attribute->getUsedForSortBy() && $attributeType == self::ATTRIBUTE_TYPE_TEXT) {
                $textAttributeConfig = [
                    'type' => self::ATTRIBUTE_TYPE_KEYWORD,
                    'index' => false,
                ];

                if (!$useCustomAnalyzer) {
                    $textAttributeConfig['normalizer'] = DefaultBuilder::LOWERCASE_NORMALIZER;
                }

                $allAttributes[$attributeCode]['fields']['sort_' . $attributeCode] = $textAttributeConfig;
            }

            if ($attribute->usesSource()
                || $attribute->getFrontendInput() === 'select'
                || $attribute->getFrontendInput() === 'multiselect'
            ) {
                $allAttributes[$attributeCode]['type'] = self::ATTRIBUTE_TYPE_KEYWORD;

                $allAttributes[$attributeCode . '_value'] = [
                    'type' => self::ATTRIBUTE_TYPE_TEXT,
                ];
            }
        }

        return $allAttributes;
    }

    /**
     * @param AbstractAttribute $attribute
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function isSearchable(AbstractAttribute $attribute)
    {
        return $attribute->getIsSearchable()
            && ($attribute->getIsVisibleInAdvancedSearch()
                || $attribute->getIsFilterable()
                || $attribute->getIsFilterableInSearch()
            )
            || (in_array($attribute->getAttributeCode(), self::DEFAULT_ATTRIBUTE_CODE_ARRAY, true));
    }

    /**
     * @param AbstractAttribute $attribute
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAttributeType(AbstractAttribute $attribute)
    {
        $backendType = $attribute->getBackendType();
        $frontendInput = $attribute->getFrontendInput();

        if ((in_array($backendType, ['int', 'smallint'], true)
                || (in_array($frontendInput, ['select', 'boolean'], true) && $backendType !== 'varchar'))
            && !$attribute->getIsUserDefined()
        ) {
            $fieldType = self::ATTRIBUTE_TYPE_INT;
        } elseif ($backendType === 'decimal') {
            $fieldType = self::ATTRIBUTE_TYPE_FLOAT;
        } else {
            if (!$this->isSearchable($attribute)) {
                if ($attribute->getIsFilterable() || $attribute->getIsFilterableInSearch()) {
                    $fieldType = self::ATTRIBUTE_TYPE_KEYWORD;
                } else {
                    $fieldType = self::ATTRIBUTE_TYPE_TEXT;
                }
            } else {
                $fieldType = self::ATTRIBUTE_TYPE_TEXT;
            }
        }

        return $fieldType;
    }
}
