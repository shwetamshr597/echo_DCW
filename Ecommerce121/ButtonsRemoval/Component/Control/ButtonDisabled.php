<?php

declare(strict_types=1);

namespace Ecommerce121\ButtonsRemoval\Component\Control;

use Magento\Ui\Component\Control\Button;

class ButtonDisabled extends Button
{
    /**
     * Return template path
     *
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return 'Ecommerce121_ButtonsRemoval::control/button/blank.phtml';
    }
}
