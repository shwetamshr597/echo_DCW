<?php
/*
 * Copyright (c) 2022 121eCommerce (https://www.121ecommerce.com/)
 */

declare(strict_types=1);

namespace Ecommerce121\EmailTestMode\Model\Email;

use Ecommerce121\EmailTestMode\Model\Config;

class Recipient
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param string|array $address
     * @return string|array
     */
    public function resolveAddress($address, $isSingle = false)
    {
        if (!$this->config->isTestModeEnabled()) {
            return $address;
        }

        $address = $this->config->getTestModeEmails();
        if ($isSingle && is_array($address)) {
            $address = current($address);
        }

        return $address;
    }
}
