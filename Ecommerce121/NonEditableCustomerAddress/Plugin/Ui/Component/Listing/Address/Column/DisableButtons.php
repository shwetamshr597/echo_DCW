<?php

declare(strict_types=1);

namespace Ecommerce121\NonEditableCustomerAddress\Plugin\Ui\Component\Listing\Address\Column;

use Magento\Customer\Ui\Component\Listing\Address\Column\Actions;

/**
 * @SuppressWarnings(UnusedFormalParameter)
 */
class DisableButtons
{
    /**
     * After prepareDataSource plugin
     *
     * @param Actions $subject
     * @param array<mixed> $result
     * @param array<mixed> $dataSource
     * @return array<mixed>
     */
    public function afterPrepareDataSource(Actions $subject, array $result, array $dataSource): array
    {
        $data = $result['data']['items'];
        if ($data) {
            foreach ($data as $key => $fields) {
                if (array_key_exists('actions', $fields)) {
                    unset($fields['actions']['edit']);
                    unset($fields['actions']['delete']);
                }
                $result['data']['items'][$key]['actions'] = $fields['actions'];
            }
            return $result;
        }

        return $result;
    }
}
