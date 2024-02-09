<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\Plugin\Magento\Catalog\Block\Product\ProductList\Toolbar;

use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Block\Product\ProductList\Toolbar;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;

class UpdateTemplateBeforeToHtmlPlugin
{
    private const TEMPLATE = 'Ecommerce121_CategoryTableView::product/list/toolbar.phtml';

    /**
     * @var LayerResolver
     */
    private $layerResolver;

    /**
     * @var array
     */
    private $allowedBlockNames;

    /**
     * @var string
     */
    private $template;

    /**
     * @param LayerResolver $layerResolver
     * @param array $allowedBlockNames
     * @param string $template
     */
    public function __construct(
        LayerResolver $layerResolver,
        array $allowedBlockNames = [],
        string $template = self::TEMPLATE
    ) {
        $this->layerResolver = $layerResolver;
        $this->allowedBlockNames = $allowedBlockNames;
        $this->template = $template;
    }

    /**
     * @param Toolbar $subject
     *
     * @return array
     */
    public function beforeToHtml(Toolbar $subject): array
    {
        if ($this->isAllowedBlock($subject) && $this->isAllowedCategory()) {
            $subject->setTemplate($this->template);
        }

        return [];
    }

    /**
     * @param Toolbar $subject
     *
     * @return bool
     */
    private function isAllowedBlock(Toolbar $subject): bool
    {
        return !empty($this->allowedBlockNames)
            && in_array($subject->getNameInLayout(), $this->allowedBlockNames, true)
            && $subject->getCurrentMode() === 'table';
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
