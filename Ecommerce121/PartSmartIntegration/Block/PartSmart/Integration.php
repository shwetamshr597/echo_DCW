<?php

declare(strict_types=1);

namespace Ecommerce121\PartSmartIntegration\Block\PartSmart;

use Ecommerce121\PartSmartIntegration\Model\Config;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Integration extends Template
{
    protected $_template = 'Ecommerce121_PartSmartIntegration::part-smart-integration.phtml';

    /**
     * @param Context $context
     * @param RedirectFactory $resultRedirectFactory
     * @param Session $customerSession
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        protected readonly Context $context,
        protected readonly RedirectFactory $resultRedirectFactory,
        protected readonly Session $customerSession,
        protected readonly Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getAccessToken(): mixed
    {
        return $this->getCurrentCustomer()->getPartSmartAccessToken();
    }

    /**
     * @return mixed
     */
    public function getRefreshToken(): mixed
    {
        return $this->getCurrentCustomer()->getPartSmartRefreshToken();
    }

    /**
     * @return string
     */
    public function getExpiresIn(): string
    {
        return (string) $this->config->getExpiresIn();
    }

    /**
     * @return Customer
     */
    public function getCurrentCustomer(): Customer
    {
        return $this->customerSession->getCustomer();
    }

    public function checkSession()
    {
        if (!$this->getCurrentCustomer()->getId()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('/');

            return $resultRedirect;
        }

        return true;
    }
}
