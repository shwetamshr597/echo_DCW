<?php
/*
 * Copyright (c) 2022 121eCommerce (https://www.121ecommerce.com/)
 */

declare(strict_types=1);

namespace Ecommerce121\EmailTestMode\Plugin\Framework\Mail\Template\TransportBuilder;

use Ecommerce121\EmailTestMode\Model\Email\Recipient;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Newsletter\Model\Queue\TransportBuilder as NewsletterTransportBuilder;

class AddBcc
{
    /**
     * @var Recipient
     */
    private $recipient;

    /**
     * @param Recipient $recipient
     */
    public function __construct(Recipient $recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @param TransportBuilder $subject
     * @param array|string $address
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeAddBcc(TransportBuilder $subject, $address): array
    {
        return [
            $this->recipient->resolveAddress($address, $subject instanceof NewsletterTransportBuilder)
        ];
    }
}
