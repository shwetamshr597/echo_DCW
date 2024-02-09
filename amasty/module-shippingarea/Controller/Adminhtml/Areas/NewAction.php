<?php

namespace Amasty\ShippingArea\Controller\Adminhtml\Areas;

use Amasty\ShippingArea\Controller\Adminhtml\Areas;

class NewAction extends Areas
{
    public function execute()
    {
        $this->_forward('edit');
    }
}
