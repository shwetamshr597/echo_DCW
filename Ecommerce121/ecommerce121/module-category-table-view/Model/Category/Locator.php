<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\Model\Category;

use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;

class Locator
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
     * @return CategoryInterface
     */
    public function get(): CategoryInterface
    {
        return $this->getCategory();
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
