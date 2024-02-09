<?php

declare(strict_types=1);

namespace Ecommerce121\CompanyDefaultWarehouse\Plugin;

use Magento\CatalogInventory\Block\Stockqty\AbstractStockqty;
use Magento\Company\Api\CompanyManagementInterface;
use Magento\Customer\Model\SessionFactory as CustomerSession;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\InventoryApi\Api\SourceItemRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class AbstractStockQtyLeft
{
    /**
     * AbstractStockQtyLeft constructor
     *
     * @param CompanyManagementInterface $companyManagement
     * @param CustomerSession $customerSessionFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SourceItemRepositoryInterface $sourceItemRepo
     */
    public function __construct(
        private readonly CompanyManagementInterface    $companyManagement,
        private readonly CustomerSession               $customerSessionFactory,
        private readonly SearchCriteriaBuilder         $searchCriteriaBuilder,
        private readonly SourceItemRepositoryInterface $sourceItemRepo,
    ) {
    }

    /**
     * Only show the inventory quantity of the customer's company's default_warehouse_id in "only X left"
     *
     * @param AbstractStockqty $subject
     * @param float $result
     * @return float
     */
    public function afterGetStockQtyLeft(AbstractStockqty $subject, float $result): float
    {
        $customerSession = $this->customerSessionFactory->create();
        $customerId = $customerSession->getCustomerId();
        if (!$customerId) {
            return $result;
        }

        $company = $this->companyManagement->getByCustomerId($customerId);
        if (!$company) { // @phpstan-ignore-line
            return $result;
        }

        $companyWarehouse = $company->getData('default_warehouse_id'); // @phpstan-ignore-line
        if (!$companyWarehouse) {
            return $result;
        }

        $newStockQtyLeft = 0;
        $sku = $subject->getProduct()->getSku();
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('sku', $sku)
            ->addFilter('source_code', $companyWarehouse)
            ->create();
        $sourceItemData = $this->sourceItemRepo->getList($searchCriteria);
        foreach ($sourceItemData->getItems() as $sourceItem) {
            if ($sourceItem->getSourceCode() === $companyWarehouse) {
                $newStockQtyLeft += $sourceItem->getQuantity();
            }
        }

        return $newStockQtyLeft;
    }
}
