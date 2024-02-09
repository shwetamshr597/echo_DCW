<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\Plugin\Magento\Catalog\Model\Category\DataProvider;

use InvalidArgumentException;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Category\DataProvider;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;

class PrepareTableViewConfigAfterGetCurrentCategoryPlugin
{
    private const IS_PROCESSED_FLAG = '_is_table_view_config_processed';

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @param JsonSerializer $jsonSerializer
     */
    public function __construct(JsonSerializer $jsonSerializer)
    {
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @param DataProvider $subject
     * @param Category $category
     *
     * @return Category
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetCurrentCategory(DataProvider $subject, Category $category): Category
    {
        if ($this->canProcess($category)) {
            $this->process($category);
        }

        return $category;
    }

    /**
     * @param Category $category
     *
     * @return bool
     */
    private function canProcess(Category $category): bool
    {
        return !(bool)$category->getData(self::IS_PROCESSED_FLAG);
    }

    /**
     * @param Category $category
     *
     * @return void
     */
    private function process(Category $category): void
    {
        $this->processUseConfig($category);
        $this->processDynamicRows($category);

        $this->markCategoryAsProcessed($category);
    }

    /**
     * @param Category $category
     *
     * @return void
     */
    private function processUseConfig(Category $category): void
    {
        $useConfigValue = false;
        $inheritanceValue = $category->getData('table_view_mode_config_inheritance');
        if ($inheritanceValue === null || $inheritanceValue === '') {
            $useConfigValue = true;
        }

        $useConfig = $category->getData('use_config') ?: [];
        $useConfig['table_view_mode_config_inheritance'] = $useConfigValue;

        $category->setData('use_config', $useConfig);
    }

    /**
     * @param Category $category
     *
     * @return void
     */
    private function processDynamicRows(Category $category): void
    {
        $value = null;

        $config = $category->getData('table_view_mode_config');
        if (!empty($config)) {
            try {
                $value = $this->jsonSerializer->unserialize($config);
            } catch (InvalidArgumentException $e) {
                $value = null;
            }
        }

        $category->setData('table_view_mode_config', $value);
    }

    /**
     * @param Category $category
     *
     * @return void
     */
    private function markCategoryAsProcessed(Category $category): void
    {
        $category->setData(self::IS_PROCESSED_FLAG, true);
    }
}
