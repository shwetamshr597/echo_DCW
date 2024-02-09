<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\Plugin\Magento\Catalog\Helper\Product\ProductList;

use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Helper\Product\ProductList;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;

class AddTableModeAfterGetAvailableViewModePlugin
{
    /**
     * @var LayerResolver
     */
    private $layerResolver;

    /**
     * @param LayerResolver $layerResolver

     */
    public function __construct(LayerResolver $layerResolver)
    {
        $this->layerResolver = $layerResolver;
    }

    /**
     * @param ProductList $subject
     * @param array $result
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetAvailableViewMode(ProductList $subject, array $result): array
    {
        if ($this->isAllowedCategory()) {
            $result = $result + ['table' => __('Table')];
        }

        return $result;
    }

    /**
     * @return bool
     */
    private function isAllowedCategory(): bool
    {
        return (bool)$this->getCategory()->getData('table_view_mode');
    }

    /**
     * @return CategoryInterface
     */
    private function getCategory(): CategoryInterface
    {
        return $this->getLayer()->getCurrentCategory();
    }

    /**
     * @return Layer
     */
    private function getLayer(): Layer
    {
        return $this->layerResolver->get();
    }
}
