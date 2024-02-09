<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\Model;

use InvalidArgumentException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private const CONFIG_ATTRIBUTES = 'ecommerce121_category_table_view/general/attributes';
    private const CONFIG_MERGE_ATTRIBUTES = 'ecommerce121_category_table_view/general/merge_layered_nav_attributes';
    private const CONFIG_SHOW_SHORT_DESCRIPTION = 'ecommerce121_category_table_view/general/display_short_description';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param JsonSerializer $jsonSerializer
     */
    public function __construct(ScopeConfigInterface $scopeConfig, JsonSerializer $jsonSerializer)
    {
        $this->scopeConfig = $scopeConfig;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * Get product attributes for category table view mode
     *
     * @param string $scopeType
     * @param null|int|string $scopeCode
     *
     * @return array
     */
    public function getAttributes(
        string $scopeType = ScopeInterface::SCOPE_STORE,
        $scopeCode = null
    ): array {
        $configValue = (string)$this->scopeConfig->getValue(self::CONFIG_ATTRIBUTES, $scopeType, $scopeCode);

        try {
            $configValue = $this->jsonSerializer->unserialize($configValue);
        } catch (InvalidArgumentException $e) {
            $configValue = [];
        }

        $attributes = [];
        if (is_array($configValue)) {
            foreach ($configValue as $configItem) {
                if (!is_array($configItem) || !array_key_exists('attribute_code', $configItem)) {
                    continue;
                }

                $attributes[] = (string)$configItem['attribute_code'];
            }
        }

        $attributes = array_unique($attributes);
        $attributes = array_filter($attributes);

        return $attributes;
    }

    /**
     * Get flag is merge layered nav attributes to configured attributes
     *
     * @param string $scopeType
     * @param null|int|string $scopeCode
     *
     * @return bool
     */
    public function isMergeLayeredNavAttributes(
        string $scopeType = ScopeInterface::SCOPE_STORE,
        $scopeCode = null
    ): bool {
        return $this->scopeConfig->isSetFlag(self::CONFIG_MERGE_ATTRIBUTES, $scopeType, $scopeCode);
    }

    /**
     * @param string $scopeType
     * @param null|int|string $scopeCode
     *
     * @return bool
     */
    public function canShowShortDescription(
        string $scopeType = ScopeInterface::SCOPE_STORE,
        $scopeCode = null
    ): bool {
        return $this->scopeConfig->isSetFlag(self::CONFIG_SHOW_SHORT_DESCRIPTION, $scopeType, $scopeCode);
    }
}
