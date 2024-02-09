<?php
/*
 * Copyright (c) 2022 121eCommerce (https://www.121ecommerce.com/)
 */

declare(strict_types=1);

namespace Ecommerce121\EmailTestMode\Plugin\Framework\Mail\Template\TransportBuilder;

use Ecommerce121\EmailTestMode\Model\Email\Recipient;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Newsletter\Model\Queue\TransportBuilder as NewsletterTransportBuilder;

class SetReplyTo
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
     * @param string $email
     * @param string|null $name
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSetReplyTo(TransportBuilder $subject, $email, $name = null): array
    {
        return [
            $this->recipient->resolveAddress($email, $subject instanceof NewsletterTransportBuilder),
            $name
        ];
    }
}
