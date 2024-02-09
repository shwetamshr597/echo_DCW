<?php

declare(strict_types=1);

namespace Ecommerce121\FixedFooter\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

class Config
{

    /**
     * Types of rates, order is important
     * Copy from \Magento\Fedex\Model\Carrier
     *
     * @var array
     */
    public static $_ratesOrder = [
        '' => 'Default',
        'name' => 'Name'
    ];

    /**
     * The configuration path for the value "SMC Manufacturer Logo / General Settings / Is Enabled".
     */
    public const GENERAL_ORDER_FIELD = 'ecommerce121_fixed_footer_order/general/order';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * @return string
     */
    public function getOrderField(): string
    {
        return (string)$this->scopeConfig->getValue(self::GENERAL_ORDER_FIELD);
    }
}
