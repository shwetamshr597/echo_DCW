<?php

namespace Ecommerce121\AddressTypes\Plugin\Customer\Controller\Adminhtml\Address;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Controller\Adminhtml\Address\DefaultBillingAddress;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;

class ValidateIfBillingAddress
{
    /**
     * ValidateIfBillingAddress constructor
     * @param AddressRepositoryInterface $addressRepository
     * @param ResultJsonFactory $resultJsonFactory
     */
    public function __construct(
        private readonly AddressRepositoryInterface $addressRepository,
        private readonly ResultJsonFactory          $resultJsonFactory
    ) {
    }

    /**
     * Around execute plugin
     *
     * @param DefaultBillingAddress $subject
     * @param callable $proceed
     * @return Json
     */
    public function aroundExecute(DefaultBillingAddress $subject, callable $proceed): Json
    {
        $addressId = $subject->getRequest()->getParam('id', false);
        $message = __("This address isn't of 'Billing' type.");

        if ($addressId) {
            try {
                $address = $this->addressRepository->getById($addressId); //@phpstan-ignore-next-line
                if ($address->getCustomAttribute('address_type')->getValue() == 'billing') {
                    return $proceed();
                }

            } catch (\Exception $e) {
                $message = __('We can\'t change default billing address right now.');
            }
        }

        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData(
            [
                'message' => $message,
                'error' => true,
            ]
        );

        return $resultJson;
    }
}
