<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

/**
 * Backend system config array field renderer
 */
class TableAttributes extends AbstractFieldArray
{
    /**
     * @var Attributes
     */
    private $attributeRenderer;

    /**
     * Prepare to render
     *
     * @throws LocalizedException
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn(
            'attribute_code',
            [
                'label' => __('Attribute'),
                'renderer' => $this->getAttributeRenderer(),
            ]
        );

        $this->_addAfter = false;
    }

    /**
     * Retrieve Attributes renderer
     *
     * @return Attributes
     * @throws LocalizedException
     */
    private function getAttributeRenderer(): Attributes
    {
        if (!$this->attributeRenderer) {
            $this->attributeRenderer = $this->getLayout()->createBlock(
                Attributes::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->attributeRenderer;
    }

    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     *
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $attributeCode = $row->getAttributeCode();
        $options = [];
        if ($attributeCode) {
            $optionHash = $this->getAttributeRenderer()->calcOptionHash($attributeCode);
            $options['option_' . $optionHash] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }
}
