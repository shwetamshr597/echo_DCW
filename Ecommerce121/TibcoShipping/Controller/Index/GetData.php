<?php

declare(strict_types=1);

namespace Ecommerce121\TibcoShipping\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Ecommerce121\ERPConnector\Helper\TibcoShipping;

class GetData extends Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var TibcoShipping
     */
    private $_tibcoShipping;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param TibcoShipping $tibcoShipping
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        TibcoShipping $tibcoShipping
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_tibcoShipping = $tibcoShipping;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->_tibcoShipping->getFreightCalculation();
        $result = $this->resultJsonFactory->create();

        return $result->setData($data);
    }
}
