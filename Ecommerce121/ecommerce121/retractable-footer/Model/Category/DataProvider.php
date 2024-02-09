<?php
/**
 * Copyright (c) 2021 121Ecommerce (https://www.121ecommerce.com/)
 */
declare(strict_types=1);

namespace Ecommerce121\FixedFooter\Model\Category;

/**
 * Category form data provider.
 */
class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{
    /**
     * List of fields groups and fields.
     *
     * @return array
     */
    protected function getFieldsMap()
    {
        $fields = parent::getFieldsMap();
        $fields['content'][] = 'category_footer_icon';

        return $fields;
    }
}
