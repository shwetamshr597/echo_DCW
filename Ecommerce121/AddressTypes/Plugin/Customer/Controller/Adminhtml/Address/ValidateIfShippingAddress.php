<?php

namespace Ecommerce121\AddressTypes\Plugin\Customer\Controller\Adminhtml\Address;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Controller\Adminhtml\Address\DefaultShippingAddress;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;

class ValidateIfShippingAddress
{
    /**
     * ValidateIfShippingAddress constructor
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
     * @param DefaultShippingAddress $subject
     * @param callable $proceed
     * @return Json
     */
    public function aroundExecute(DefaultShippingAddress $subject, callable $proceed): Json
    {
        $addressId = $subject->getRequest()->getParam('id', false);
        $message = __("This address isn't of 'Shipping' type.");

        if ($addressId) {
            try {
                $address = $this->addressRepository->getById($addressId); //@phpstan-ignore-next-line
                if ($address->getCustomAttribute('address_type')->getValue() == 'shipping') {
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
