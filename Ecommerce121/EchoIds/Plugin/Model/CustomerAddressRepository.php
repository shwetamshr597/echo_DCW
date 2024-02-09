<?php
declare(strict_types=1);

namespace Ecommerce121\EchoIds\Plugin\Model;

use Ecommerce121\EchoIds\Model\EchoCompanyFieldInterface;

class CustomerAddressRepository
{
    public function afterGetById(
        $subject,
        \Magento\Customer\Api\Data\CustomerInterface $result
    ) {
        $customerAddresses = $result->getAddresses();

        foreach ($customerAddresses as $customerAddress) {
                if (isset($customerAddress->getCustomAttributes()[EchoCompanyFieldInterface::ECHO_LOC_ID])) {
                $echoLocId = $customerAddress->getCustomAttributes()[EchoCompanyFieldInterface::ECHO_LOC_ID]->getValue() ?? null;
                $extensionAttributes = $customerAddress->getExtensionAttributes();
                $echoLocId ? $extensionAttributes->setEchoLocId($echoLocId) : $extensionAttributes->setEchoLocId('');
                $customerAddress->setExtensionAttributes($extensionAttributes);
            }
        }

        return $result;
    }
}
