<?php

declare(strict_types=1);

namespace Ecommerce121\CompanyDefaultWarehouse\Plugin\CompanyRepository;

use Ecommerce121\EchoIds\Model\EchoCompanyFieldInterface;
use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Company\Api\Data\CompanyInterface;
use Magento\Company\Model\Company;
use Magento\Framework\App\RequestInterface;

/**
 * Set data to Company itself from its extension attributes to save these values to `company` DB table.
 */
class SaveDefaultWarehousePlugin
{

    /**
     * SaveDefaultWarehousePlugin constructor
     * @param RequestInterface $request
     */
    public function __construct(private readonly RequestInterface $request)
    {
    }

    /**
     * Persist the default_warehouse_id attribute on Company save
     *
     * @param CompanyRepositoryInterface $subject
     * @param CompanyInterface $company
     * @return CompanyInterface[]|Company[]
     */
    public function beforeSave(
        CompanyRepositoryInterface $subject,
        CompanyInterface           $company
    ): array {
        $params = $this->request->getParams();
        $ea = $company->getExtensionAttributes();
        $defaultWarehouseId = isset($params['general']) ? $params['general']['default_warehouse_id'] : $ea->getDefaultWarehouseId();

            /** @var Company $company */
            $company->setData('default_warehouse_id', $defaultWarehouseId);

        return [$company];
    }
}
