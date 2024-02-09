<?php
/*
 * Copyright (c) 2021 121Ecommerce (https://www.121ecommerce.com/)
 */
declare(strict_types=1);

namespace Ecommerce121\StickyHeader\Model\Store;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /**
     * Path to configuration for product simple
     */
    const XML_PDP_PRODUCT_TYPE_SIMPLE = 'ecommerce121_sticky_header/pdp_simple/product_type';

    /**
     * Path to configuration for product configurable
     */
    const XML_PDP_PRODUCT_TYPE_CONFIGURABLE = 'ecommerce121_sticky_header/pdp_configurable/product_type';

    /**
     * Path to configuration for product configurable max options
     */
    const XML_PDP_PRODUCT_TYPE_CONFIGURABLE_MAX_OPTIONS = 'ecommerce121_sticky_header/pdp_configurable/max_options';

    /**
     * Path to configuration for bundle
     */
    const XML_PDP_PRODUCT_TYPE_BUNDLE = 'ecommerce121_sticky_header/pdp_bundle/product_type';

    /**
     * Path to configuration for grouped
     */
    const XML_PDP_PRODUCT_TYPE_GROUPED = 'ecommerce121_sticky_header/pdp_grouped/product_type';

    /**
     * Path to configuration for virtual
     */
    const XML_PDP_PRODUCT_TYPE_VIRTUAL = 'ecommerce121_sticky_header/pdp_virtual/product_type';

    /**
     * Path to configuration for downloadable
     */
    const XML_PDP_PRODUCT_TYPE_DOWNLOADABLE = 'ecommerce121_sticky_header/pdp_downloadable/product_type';

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
     * Get Include in sticky value from configuration backend for simple product
     * @return bool
     *
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIncludeInStickyHeaderSimpleProduct()
    {
        return $this->scopeConfig->getValue(
            self::XML_PDP_PRODUCT_TYPE_SIMPLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Include in sticky value from configuration backend for configurable product
     * @return bool
     *
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIncludeInStickyHeaderConfigurableProduct()
    {
        return $this->scopeConfig->getValue(
            self::XML_PDP_PRODUCT_TYPE_CONFIGURABLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get max options value from configuration backend for configurable product
     * @return string
     */
    public function getMaxOptionsForConfigurableProduct()
    {
        return $this->scopeConfig->getValue(
            self::XML_PDP_PRODUCT_TYPE_CONFIGURABLE_MAX_OPTIONS,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Include in sticky value from configuration backend for bundle product
     * @return bool
     *
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIncludeInStickyHeaderBundleProduct()
    {
        return $this->scopeConfig->getValue(
            self::XML_PDP_PRODUCT_TYPE_BUNDLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Include in sticky value from configuration backend for grouped product
     * @return bool
     *
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIncludeInStickyHeaderGroupedProduct()
    {
        return $this->scopeConfig->getValue(
            self::XML_PDP_PRODUCT_TYPE_GROUPED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Include in sticky value from configuration backend for virtual product
     * @return bool
     *
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIncludeInStickyHeaderVirtualProduct()
    {
        return $this->scopeConfig->getValue(
            self::XML_PDP_PRODUCT_TYPE_VIRTUAL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Include in sticky value from configuration backend for downloadable product
     * @return bool
     *
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIncludeInStickyHeaderDownloadableProduct()
    {
        return $this->scopeConfig->getValue(
            self::XML_PDP_PRODUCT_TYPE_DOWNLOADABLE,
            ScopeInterface::SCOPE_STORE
        );
    }
}
