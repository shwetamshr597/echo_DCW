<?php

declare(strict_types=1);

namespace Ecommerce121\RestrictCompanyCRUD\Plugin\Ui\Component\Listing\Column;

use Magento\Company\Ui\Component\Listing\Role\Column\Actions;

class RemoveRoleActions
{
    /**
     * Remove actions from Roles and Permissions
     *
     * @param Actions $subject
     * @param array<mixed> $result
     * @param array<mixed> $dataSource
     * @return array<mixed>
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterPrepareDataSource(Actions $subject, array $result, array $dataSource): array
    {
        foreach ($result['data']['items'] as &$item) {
            unset($item['actions']);
        }
        return $result;
    }
}
