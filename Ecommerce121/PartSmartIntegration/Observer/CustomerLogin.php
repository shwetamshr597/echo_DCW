<?php

declare(strict_types=1);

namespace Ecommerce121\PartSmartIntegration\Observer;

use Ecommerce121\PartSmartIntegration\Constants;
use Ecommerce121\PartSmartIntegration\Model\PartSmart;
use JetBrains\PhpStorm\NoReturn;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\State\InputMismatchException;

class CustomerLogin implements ObserverInterface
{

    /**
     * @param PartSmart $partSmart
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        private readonly PartSmart $partSmart,
        private readonly CustomerRepositoryInterface $customerRepository
    )
    {}

    /**
     * @throws NoSuchEntityException
     * @throws InputMismatchException
     * @throws LocalizedException
     * @throws InputException
     */
    #[NoReturn]
    public function execute(Observer $observer): void
    {
        $customerEvent = $observer->getEvent()->getCustomer();

        $customer = $this->customerRepository->getById($customerEvent->getId());
        $partSmartAuth = $this->partSmart->portalUserAuthenticate($customer);

        $customer->setCustomAttribute(Constants::EAV_CUSTOMER_ACCESS_TOKEN, $partSmartAuth['access_token']);
        $customer->setCustomAttribute(Constants::EAV_CUSTOMER_REFRESH_TOKEN, $partSmartAuth['refresh_token']);

        $this->customerRepository->save($customer);
    }
}