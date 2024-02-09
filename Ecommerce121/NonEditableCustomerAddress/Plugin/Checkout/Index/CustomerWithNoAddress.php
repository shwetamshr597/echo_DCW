<?php

declare(strict_types=1);

namespace Ecommerce121\NonEditableCustomerAddress\Plugin\Checkout\Index;

use Magento\Checkout\Controller\Index\Index;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\ResultInterface;

/**
 * @SuppressWarnings(UnusedFormalParameter)
 */
class CustomerWithNoAddress
{
    /**
     * CustomerWithNoAddress constructor
     *
     * @param Session $session
     * @param RedirectFactory $redirectFactory
     * @param ManagerInterface $manager
     */
    public function __construct(
        private readonly Session $session,
        private readonly RedirectFactory $redirectFactory,
        private readonly ManagerInterface $manager
    ) {
    }

    /**
     * After execute plugin
     *
     * @param Index $subject
     * @param ResultInterface $result
     * @return Redirect|ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterExecute(Index $subject, ResultInterface $result): Redirect|ResultInterface
    {
        if (!$this->session->isLoggedIn()) {
            return $result;
        }

        $address = $this->session->getCustomerData()->getAddresses();
        if (is_array($address) && count($address) == 0) {
            $this->manager->addWarningMessage(__('Contact the store for assistance, seems like
            you don\'t have any address set.')->getText());
            return $this->redirectFactory->create()->setPath('contact');
        }
        return $result;
    }
}
