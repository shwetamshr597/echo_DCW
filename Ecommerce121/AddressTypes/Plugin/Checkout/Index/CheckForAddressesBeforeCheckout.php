<?php

namespace Ecommerce121\AddressTypes\Plugin\Checkout\Index;

use Magento\Checkout\Controller\Index\Index;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;

class CheckForAddressesBeforeCheckout
{
    /**
     * CheckAddressAttribute constructor.
     * @param RedirectFactory $redirectFactory
     * @param ManagerInterface $messageManager
     * @param CustomerSession $customerSession
     * @param ScopeConfigInterface $config
     */
    public function __construct(
        private readonly RedirectFactory  $redirectFactory,
        private readonly ManagerInterface $messageManager,
        private readonly CustomerSession  $customerSession,
        private readonly ScopeConfigInterface $config
    ) {
    }

    /**
     * Check if customer address has custom attribute and block checkout access if not
     *
     * @param Index $subject
     * @param ResultInterface $result
     * @return Redirect|ResultInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function afterExecute(Index $subject, ResultInterface $result): Redirect|ResultInterface
    {
        if (!$this->customerSession->isLoggedIn()) {
            return $result;
        }

        if ($this->config->getValue('ecommerce121_customer_address/general/disable')) {
            return $result;
        }

        $customer = $this->customerSession->getCustomer();
        $customerAddresses = $customer->getAddresses();

        if (!empty($customerAddresses)) {
            $hasShippingAddress = false;
            $hasBillingAddress = false;

            foreach ($customerAddresses as $address) {
                if ($address->getData('address_type') == 'shipping') {
                    $hasShippingAddress = true;
                } elseif ($address->getData('address_type') == 'billing') {
                    $hasBillingAddress = true;
                }

                if ($hasShippingAddress && $hasBillingAddress) {
                    break;
                }
            }

            if (!($hasShippingAddress && $hasBillingAddress)) {
                $this->messageManager->addWarningMessage(__('Contact the store for assistance, seems like
                    you don\'t have your addresses correctly set.')->getText());
                return $this->redirectFactory->create()->setPath('contact');
            }
        }

        return $result;
    }
}
