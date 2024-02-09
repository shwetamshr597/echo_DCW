<?php

declare(strict_types=1);

namespace Ecommerce121\EchoIds\Plugin\Model\CompanyRepository\Api;

use Magento\Company\Api\Data\CompanyInterface;
use Magento\Company\Model\CompanyRepository;
use Ecommerce121\EchoIds\Model\EchoCompanyFieldInterface;

class GetEchoCompanyFieldApi
{
    /**
     * After get plugin
     *
     * @param CompanyRepository $subject
     * @param CompanyInterface $result
     * @param int $companyId
     * @return CompanyInterface
     * @SuppressWarnings(UnusedFormalParameter)
     */
    public function afterGet(CompanyRepository $subject, CompanyInterface $result, int $companyId): CompanyInterface
    {
        //@phpstan-ignore-next-line
        $echoCustId = $result->getData(EchoCompanyFieldInterface::ECHO_CUST_ID);
        //@phpstan-ignore-next-line
        $echoCustClass = $result->getData(EchoCompanyFieldInterface::ECHO_CUST_CLASS);
        //@phpstan-ignore-next-line
        $echoSalesRepId = $result->getData(EchoCompanyFieldInterface::ECHO_SALES_REP_ID);

        $extensionAttributes = $result->getExtensionAttributes();
        //@phpstan-ignore-next-line
        $extensionAttributes->setEchoCustId($echoCustId ?? '');
        //@phpstan-ignore-next-line
        $extensionAttributes->setEchoCustClass($echoCustClass ?? '');
        //@phpstan-ignore-next-line
        $extensionAttributes->setEchoSalesRepId($echoSalesRepId ?? '');

        //@phpstan-ignore-next-line
        $result->setExtensionAttributes($extensionAttributes);

        return $result;
    }
}
