<?php
/**
 * Copyright (c) 2021 121Ecommerce (https://www.121ecommerce.com/)
 */
declare(strict_types=1);

namespace Ecommerce121\FixedFooter\Controller\Catalog\Adminhtml\Category;

class Save extends \Magento\Catalog\Controller\Adminhtml\Category\Save
{
    /**
     * Workaround
     * Solve a bug, that when user try to save a category with footer option,
     * and max categories allowed already satisfied, the icon image show wrong
     * preview.
     *
     * @param array $data
     * @return array
     */
    public function imagePreprocessing($data): array
    {
        $dataLocal = parent::imagePreprocessing($data);

        if ($dataLocal['category_footer_icon'] == false) {
            $dataLocal['category_footer_icon'] = null;
        }

        return $dataLocal;
    }
}
