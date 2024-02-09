<?php

declare(strict_types=1);

namespace Ecommerce121\CompanyDefaultWarehouse\Observer;

use Magento\Company\Api\Data\CompanyInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Company\Api\CompanyManagementInterface;

class AssignWarehouseToOrder implements ObserverInterface
{
    /**
     * AssignWarehouseToOrder constructor
     *
     * @param CompanyManagementInterface $companyManagement
     */
    public function __construct(
        private readonly CompanyManagementInterface  $companyManagement
    ) {
    }

    /**
     * Execute observer
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getData('order');
        $customerId = $order->getCustomerId();

        if (!$customerId) {
            return;
        }

        /** @var CompanyInterface|null $company */
        $company = $this->companyManagement->getByCustomerId($customerId);

        if (!$company) {
            return;
        }

        $order->setData('company_warehouse', $company->getData('default_warehouse_id')); // @phpstan-ignore-line
    }
}
