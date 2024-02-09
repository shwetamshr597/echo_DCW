<?php
/*
 * Copyright (c) 2022 121eCommerce (https://www.121ecommerce.com/)
 */

declare(strict_types=1);

namespace Ecommerce121\EmailTestMode\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    private const XML_PATH_EMAIL_TEST_MODE_ENABLED = 'system/smtp/test_mode';
    private const XML_PATH_EMAIL_TEST_MODE_EMAILS = 'system/smtp/test_emails';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isTestModeEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_EMAIL_TEST_MODE_ENABLED);
    }

    /**
     * @return array
     */
    public function getTestModeEmails(): array
    {
        $emails = $this->scopeConfig->getValue(self::XML_PATH_EMAIL_TEST_MODE_EMAILS);
        return $emails ? array_map('trim', explode(',', $emails)) : [];
    }
}
