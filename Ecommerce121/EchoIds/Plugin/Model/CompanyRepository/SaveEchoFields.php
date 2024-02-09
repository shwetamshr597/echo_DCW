<?php

declare(strict_types=1);

namespace Ecommerce121\EchoIds\Plugin\Model\CompanyRepository;

use Magento\Company\Api\Data\CompanyInterface;
use Magento\Company\Model\CompanyRepository;
use Magento\Framework\App\RequestInterface;
use Ecommerce121\EchoIds\Model\EchoCompanyFieldInterface;

class SaveEchoFields
{
    /**
     * SaveEchoFields constructor
     *
     * @param RequestInterface $request
     */
    public function __construct(private readonly RequestInterface $request)
    {
    }

    /**
     * Before Save Plugin
     *
     * @param CompanyRepository $subject
     * @param CompanyInterface $company
     * @return CompanyInterface[]
     */
    public function beforeSave(CompanyRepository $subject, CompanyInterface $company): array
    {
        $params = $this->request->getParams();
        $ea = $company->getExtensionAttributes();
        $echoCustId = isset($params['general']) ? $params['general'][EchoCompanyFieldInterface::ECHO_CUST_ID] : $ea->getEchoCustId();
        $echoSalesRepId = isset($params['general']) ? $params['general'][EchoCompanyFieldInterface::ECHO_SALES_REP_ID] : $ea->getEchoSalesRepId();
        $echoCustClass = isset($params['general']) ? $params['general'][EchoCompanyFieldInterface::ECHO_CUST_CLASS] : $ea->getEchoCustClass();

        // @phpstan-ignore-next-line
        $company->setData(EchoCompanyFieldInterface::ECHO_CUST_CLASS, $echoCustClass);
        // @phpstan-ignore-next-line
        $company->setData(EchoCompanyFieldInterface::ECHO_SALES_REP_ID, $echoSalesRepId);
        // @phpstan-ignore-next-line
        $company->setData(EchoCompanyFieldInterface::ECHO_CUST_ID, $echoCustId);

        return [$company];
    }
}
