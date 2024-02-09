<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\Model\Config\Source;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection as AttributeCollection;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Framework\Data\Collection;
use Magento\Framework\Data\OptionSourceInterface;

class Attribute implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var string[]
     */
    private $excludedAttributes;

    /**
     * @var array
     */
    private $options;

    /**
     * @param CollectionFactory $collectionFactory
     * @param string[] $excludedAttributes
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        array $excludedAttributes = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->excludedAttributes = $excludedAttributes;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        if (!$this->options) {
            $this->options[] = ['value' => 'sku', 'label' => 'SKU'];

            foreach ($this->getAttributes() as $attribute) {
                $this->options[] = [
                    'value' => $attribute->getAttributeCode(),
                    'label' => $attribute->getFrontendLabel(),
                ];
            }
        }

        return $this->options;
    }

    /**
     * @return AttributeCollection|ProductAttributeInterface[]
     */
    private function getAttributes(): AttributeCollection
    {
        return $this->collectionFactory->create()
            ->addFieldToFilter('attribute_code', ['nin' => $this->excludedAttributes])
            ->addFieldToFilter(
                'frontend_input',
                ['nin' => ['media_image', 'textarea', 'image', 'hidden']]
            )
            ->addFieldToFilter('used_in_product_listing', 1)
            ->setOrder('frontend_label', Collection::SORT_ORDER_ASC);
    }
}
