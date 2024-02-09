<?php

declare(strict_types=1);

namespace Ecommerce121\RestrictCompanyCRUD\Plugin\Ui\Component\Listing\Column;

use Magento\Company\Ui\Component\Listing\Column\CompanyUsersActions;

class RemoveDeleteAction
{
    /**
     * Remove delete action from data source
     *
     * @param CompanyUsersActions $subject
     * @param array<mixed> $result
     * @param array<mixed> $dataSource
     * @return array<mixed>
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterPrepareDataSource(CompanyUsersActions $subject, array $result, array $dataSource): array
    {
        foreach ($result['data']['items'] as &$item) {
            unset($item['actions']['delete']);
        }

        return $result;
    }
}
