<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\Observer;

use Ecommerce121\CategoryTableView\Model\Category\GetFilters;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class JoinAttributesObserver implements ObserverInterface
{
    /**
     * @var GetFilters
     */
    private $getFilters;

    /**
     * @param GetFilters $getFilters
     */
    public function __construct(GetFilters $getFilters)
    {
        $this->getFilters = $getFilters;
    }

    /**
     * @param EventObserver $observer
     *
     * @return void
     */
    public function execute(EventObserver $observer): void
    {
        $collection = $observer->getEvent()->getData('collection');
        if ($collection instanceof Collection) {
            $attributeCodes = $this->getAttributeCodes();
            if (!empty($attributeCodes)) {
                $collection->addAttributeToSelect($attributeCodes);
            }
        }
    }

    /**
     * @return string[]
     */
    private function getAttributeCodes(): array
    {
        return array_keys($this->getFilters->execute());
    }
}
