<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Attachments Base for Magento 2
 */

namespace Amasty\ProductAttachment\Model\SourceOptions;

use Magento\Framework\Option\ArrayInterface;

class WidgetType implements ArrayInterface
{
    public const CURRENT_CATEGORY = 1;
    public const SPECIFIC_CATEGORY = 2;
    public const CURRENT_PRODUCT = 3;
    public const SPECIFIC_PRODUCT = 4;
    public const CUSTOM_FILES = 5;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $optionArray = [];
        foreach ($this->toArray() as $widgetType => $label) {
            $optionArray[] = ['value' => $widgetType, 'label' => $label];
        }
        return $optionArray;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            self::CURRENT_CATEGORY => __('Current Category'),
            self::SPECIFIC_CATEGORY => __('Specific Category'),
            self::CURRENT_PRODUCT => __('Current Product'),
            self::SPECIFIC_PRODUCT => __('Specific Product'),
            self::CUSTOM_FILES => __('Custom Files'),
        ];
    }
}
