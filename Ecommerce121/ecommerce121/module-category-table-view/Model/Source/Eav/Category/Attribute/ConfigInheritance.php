<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\Model\Source\Eav\Category\Attribute;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\OptionSourceInterface;

class ConfigInheritance extends AbstractSource implements OptionSourceInterface
{
    public const USE_CUSTOM = 1;
    public const USE_CUSTOM_LABEL = 'Use Custom Config';

    public const INHERIT_FROM_PARENT_CATEGORY = 0;
    public const INHERIT_FROM_PARENT_CATEGORY_LABEL = 'Inherit Config from Parent Category';

    /**
     * @return array
     */
    public function getAllOptions(): array
    {
        if (null === $this->_options) {
            $this->_options = [];
            foreach ($this->getOptionsMap() as $value => $label) {
                $this->_options[] = [
                    'value' => $value,
                    'label' => __($label),
                ];
            }
        }

        return $this->_options;
    }

    /**
     * @return array
     */
    private function getOptionsMap(): array
    {
        return [
            self::INHERIT_FROM_PARENT_CATEGORY => self::INHERIT_FROM_PARENT_CATEGORY_LABEL,
            self::USE_CUSTOM => self::USE_CUSTOM_LABEL,
        ];
    }
}
