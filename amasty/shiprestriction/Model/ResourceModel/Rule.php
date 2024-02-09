<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Shipping Restrictions for Magento 2
*/

namespace Amasty\Shiprestriction\Model\ResourceModel;

use Amasty\Shiprestriction\Model\ConstantsInterface;

class Rule extends \Amasty\CommonRules\Model\ResourceModel\AbstractRule
{
    public const TABLE_NAME = 'amasty_shiprestriction_rule';
    public const ATTRIBUTE_TABLE_NAME = 'amasty_shiprestriction_attribute';

    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'rule_id');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        foreach (ConstantsInterface::FIELDS as $field) {
            // convert data from array to string
            $value = $object->getData($field);

            if (is_array($value)) {
                $object->setData($field, '');

                if ($field == 'methods') {
                    $carriers = [];

                    foreach ($value as $key => $shipMethod) {
                        if (strpos($shipMethod, '_') === false) {
                            $carriers[] = $shipMethod;
                            unset($value[$key]);
                        }
                    }
                    $object->setCarriers(implode(',', $carriers));
                }

                $object->setData($field, implode(',', $value));
            }
        }

        return parent::_beforeSave($object);
    }
}
