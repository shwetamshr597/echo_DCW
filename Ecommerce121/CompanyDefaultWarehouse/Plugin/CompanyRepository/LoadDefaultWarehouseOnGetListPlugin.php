<?php

declare(strict_types=1);

namespace Ecommerce121\CompanyDefaultWarehouse\Plugin\CompanyRepository;

use Ecommerce121\CompanyDefaultWarehouse\Model\Company\Source\WarehouseExtensionAttributeLoader;
use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Company\Api\Data\CompanySearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class LoadDefaultWarehouseOnGetListPlugin
{
    /**
     * LoadDefaultWarehouseOnGetListPlugin constructor
     * @param WarehouseExtensionAttributeLoader $warehouseExtAttrLoader
     */
    public function __construct(
        private readonly WarehouseExtensionAttributeLoader $warehouseExtAttrLoader
    ) {
    }

    /**
     * Enrich the given Company objects with the default_warehouse_id attribute
     *
     * @param CompanyRepositoryInterface $subject
     * @param CompanySearchResultsInterface|SearchResultsInterface $companySearchResults
     * @return CompanySearchResultsInterface|SearchResultsInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(
        CompanyRepositoryInterface $subject,
        CompanySearchResultsInterface|SearchResultsInterface $companySearchResults
    ): CompanySearchResultsInterface|SearchResultsInterface {
        $items = $companySearchResults->getItems();
        array_walk(
            $items,
            [$this->warehouseExtAttrLoader, 'execute'] // @phpstan-ignore-line
        );

        return $companySearchResults;
    }
}
