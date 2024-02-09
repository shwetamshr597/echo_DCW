<?php
declare(strict_types=1);

namespace Amasty\ShippingArea\Model\Validator;

use Amasty\ShippingArea\Model\Area;
use Magento\Quote\Model\Quote\Address;

interface ValidatorInterface
{
    /**
     * @param Area $area
     * @param Address $address
     * @return bool
     */
    public function isValid(Area $area, Address $address): bool;
}
