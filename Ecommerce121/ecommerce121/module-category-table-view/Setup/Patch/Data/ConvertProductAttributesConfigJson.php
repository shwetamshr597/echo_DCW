<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\Setup\Patch\Data;

use InvalidArgumentException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface as ConfigWriter;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class ConvertProductAttributesConfigJson implements DataPatchInterface
{
    private const CONFIG_ATTRIBUTES = 'ecommerce121_category_table_view/general/attributes';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ConfigWriter
     */
    private $configWriter;

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ConfigWriter $configWriter
     * @param JsonSerializer $jsonSerializer
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ConfigWriter $configWriter,
        JsonSerializer $jsonSerializer
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @return void
     */
    public function apply(): void
    {
        $this->updateValue();
    }

    /**
     * @return void
     */
    private function updateValue(): void
    {
        $this->configWriter->save(self::CONFIG_ATTRIBUTES, $this->getConvertedValue());
    }

    /**
     * @return string
     */
    private function getConvertedValue(): string
    {
        $value = $this->getValue();
        if (empty($value)) {
            return '';
        }

        try {
            $value = $this->jsonSerializer->unserialize($value);
        } catch (InvalidArgumentException $e) {
            $value = '';
        }

        if (!is_array($value) || empty($value)) {
            return '';
        }

        $convertedValue = [];
        foreach (array_keys($value) as $attributeCode) {
            $convertedValue[$attributeCode] = ['attribute_code' => $attributeCode];
        }

        try {
            $convertedValue = $this->jsonSerializer->serialize($convertedValue);
        } catch (InvalidArgumentException $e) {
            $convertedValue = '';
        }

        return $convertedValue;
    }

    /**
     * @return string
     */
    private function getValue(): string
    {
        return (string)$this->scopeConfig->getValue(self::CONFIG_ATTRIBUTES);
    }

    /**
     * @return string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    public function getAliases(): array
    {
        return [];
    }
}
