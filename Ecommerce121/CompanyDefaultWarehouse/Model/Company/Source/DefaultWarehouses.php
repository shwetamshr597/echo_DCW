<?php

declare(strict_types=1);

namespace Ecommerce121\CompanyDefaultWarehouse\Model\Company\Source;

use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\InventoryApi\Api\SourceRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class DefaultWarehouses implements \Magento\Framework\Data\OptionSourceInterface
{
    /** @var array<mixed> $options */
    private array $options;

    /**
     * DefaultWarehouses constructor
     *
     * @param SourceRepositoryInterface $sourceRepository
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     */
    public function __construct(
        private readonly SourceRepositoryInterface    $sourceRepository,
        private readonly SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
    ) {
        $this->options = [];
    }

    /**
     * Gather all inventory_source options to populate select
     *
     * @return array<mixed>
     */
    public function toOptionArray(): array
    {
        if (!$this->options) {
            $this->options[] = ['value' => '', 'label' => 'Please Select123...'];

            $this->options[] = ['value' => '1', 'label' => 'Warehouse 1'];
           
            $this->options[] = ['value' => '201', 'label' => 'Warehouse 2']; 
            // $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
            // /** @var SearchCriteria $searchCriteria */
            // $searchCriteria = $searchCriteriaBuilder->create();
            // $sources = $this->sourceRepository->getList($searchCriteria)->getItems();
            // foreach ($sources as $source) {
            //     $this->options[] = [
            //         'value' => $source->getSourceCode(),
            //         'label' => __($source->getSourceCode() . ' - ' . $source->getName())
            //     ];
            // }
        }
        return $this->options;
    }
}
