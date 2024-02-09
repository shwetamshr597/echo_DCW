<?php

namespace Amasty\ShippingArea\Ui\Component\Form\Button;

class DuplicateButton extends AbstractButton
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        $data = [];

        if ($this->isAllowed()) {
            $data =  [
                'label' => __('Duplicate'),
                'class' => 'duplicate',
                'sort_order' => 30,
                'url' => $this->getUrl('*/*/duplicate', ['id' => $this->getCurrentId()])
            ];
        }

        return $data;
    }
}
