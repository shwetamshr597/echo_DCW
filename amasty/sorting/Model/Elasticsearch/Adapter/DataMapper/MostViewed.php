<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Improved Sorting for Magento 2
 */

namespace Amasty\Sorting\Model\Elasticsearch\Adapter\DataMapper;

use Amasty\Sorting\Model\Elasticsearch\Adapter\IndexedDataMapper;

class MostViewed extends IndexedDataMapper
{
    public const FIELD_NAME = 'most_viewed';

    /**
     * @inheritdoc
     */
    public function getIndexerCode()
    {
        return 'amasty_sorting_most_viewed';
    }
}
