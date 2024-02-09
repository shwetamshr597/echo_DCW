<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\Model\Category;

use Ecommerce121\CategoryTableView\Model\Config;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\Layer\FilterList;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Framework\Exception\LocalizedException;

class GetFilters
{
    /**
     * @var Locator
     */
    private $locator;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var ConfigResolver
     */
    private $configResolver;

    /**
     * @var ProductResource
     */
    private $productResource;

    /**
     * @var FilterList
     */
    private $filterList;

    /**
     * @var LayerResolver
     */
    private $layerResolver;

    /**
     * @var string[]
     */
    private $defaultAttributes;

    /**
     * @var array
     */
    private $filters;

    /**
     * @param Locator $locator
     * @param Config $config
     * @param ConfigResolver $configResolver
     * @param ProductResource $productResource
     * @param FilterList $filterList
     * @param LayerResolver $layerResolver
     * @param array $defaultAttributes
     */
    public function __construct(
        Locator $locator,
        Config $config,
        ConfigResolver $configResolver,
        ProductResource $productResource,
        FilterList $filterList,
        LayerResolver $layerResolver,
        array $defaultAttributes = []
    ) {
        $this->locator = $locator;
        $this->config = $config;
        $this->productResource = $productResource;
        $this->filterList = $filterList;
        $this->defaultAttributes = $defaultAttributes;
        $this->layerResolver = $layerResolver;
        $this->configResolver = $configResolver;
    }

    /**
     * @return array
     */
    public function execute(): array
    {
        if ($this->filters === null) {
            $this->filters = [];

            foreach ($this->getAttributes() as $attributeCode) {
                try {
                    $attribute = $this->productResource->getAttribute($attributeCode);
                    $this->filters[$attributeCode] = $attribute->getStoreLabel();
                } catch (LocalizedException $e) {
                    continue;
                }
            }

            if ($this->config->isMergeLayeredNavAttributes()) {
                foreach ($this->filterList->getFilters($this->layerResolver->get()) as $filter) {
                    try {
                        $attribute = $filter->getAttributeModel();
                    } catch (LocalizedException $e) {
                        continue;
                    }

                    if ($filter->getItemsCount()) {
                        $this->filters[$attribute->getAttributeCode()] = $attribute->getStoreLabel();
                    }
                }
            }
        }

        return $this->filters;
    }

    /**
     * @return string[]
     */
    private function getAttributes(): array
    {
        if ($this->canUseGlobalConfig()) {
            $attributes = $this->config->getAttributes() ?: $this->defaultAttributes;
        } else {
            $attributes = $this->getAttributesByCategory();
        }

        return $attributes ?: $this->defaultAttributes;
    }

    /**
     * @return bool
     */
    private function canUseGlobalConfig(): bool
    {
        return $this->getCategory()->getData('table_view_mode_config_inheritance') === null;
    }

    /**
     * @return array|null
     */
    private function getAttributesByCategory(): ?array
    {
        return $this->configResolver->get($this->getCategory());
    }

    /**
     * @return CategoryInterface
     */
    private function getCategory(): CategoryInterface
    {
        return $this->locator->get();
    }
}
