<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\Block\Adminhtml\Form\Field;

use Ecommerce121\CategoryTableView\Model\Config\Source\Attribute;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

/**
 * HTML select element block
 *
 * @method setName(string $value)
 */
class Attributes extends Select
{
    /**
     * Constructor
     *
     * @param Context $context
     * @param Attribute $attribute
     * @param array $data
     */
    public function __construct(Context $context, Attribute $attribute, array $data = [])
    {
        parent::__construct($context, $data);
        $this->_options = $attribute->toOptionArray();
    }

    /**
     * Sets name for input element
     *
     * @param string $value
     *
     * @return Attributes
     */
    public function setInputName($value): Attributes
    {
        return $this->setName($value);
    }
}
