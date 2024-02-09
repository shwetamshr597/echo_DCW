<?php

declare(strict_types=1);

namespace Ecommerce121\EchoIds\Plugin\Model\Company\DataProvider;

use Magento\Company\Api\Data\CompanyInterface;
use Magento\Company\Model\Company\DataProvider;
use Ecommerce121\EchoIds\Model\EchoCompanyFieldInterface;

class GetEchoFields
{
    /**
     * GetEchoFields constructor
     *
     * @param DataProvider $subject
     * @param array<mixed> $result
     * @param CompanyInterface $company
     * @return array<mixed>
     * @SuppressWarnings(UnusedFormalParameter)
     */
    public function afterGetGeneralData(DataProvider $subject, array $result, CompanyInterface $company): array
    {
        $echoFields = [
            EchoCompanyFieldInterface::ECHO_CUST_CLASS =>
                //@phpstan-ignore-next-line
                $company->getData(EchoCompanyFieldInterface::ECHO_CUST_CLASS),
            EchoCompanyFieldInterface::ECHO_SALES_REP_ID =>
                //@phpstan-ignore-next-line
                $company->getData(EchoCompanyFieldInterface::ECHO_SALES_REP_ID),
            EchoCompanyFieldInterface::ECHO_CUST_ID =>
                //@phpstan-ignore-next-line
                $company->getData(EchoCompanyFieldInterface::ECHO_CUST_ID)
            ];
        return array_merge($result, $echoFields);
    }
}
