<?php

declare(strict_types=1);

namespace Ecommerce121\CompanyDefaultWarehouse\Ui\Component\Listing\Column;

use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class DefaultWarehouse extends Column
{
    /**
     * Prepare data source
     *
     * @param array<mixed> $dataSource
     * @return array<mixed>
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['totalRecords'])) {
            return $dataSource;
        }

        if ((int)$dataSource['data']['totalRecords'] === 0) {
            return $dataSource;
        }

        return $this->normalizeData($dataSource);
    }

    /**
     * Add 'default_warehouse_id' value to dataSource
     *
     * @param array<mixed> $dataSource
     * @return array<mixed>
     */
    private function normalizeData(array $dataSource): array
    {
        foreach ($dataSource['data']['items'] as &$row) {
            $row['default_warehouse_id'] ??=
                $row[ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]['default_warehouse_id'] ?? null;
        }

        return $dataSource;
    }
}
