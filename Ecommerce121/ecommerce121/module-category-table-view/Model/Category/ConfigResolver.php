<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\Model\Category;

use Ecommerce121\CategoryTableView\Model\ResourceModel\Category\ConfigLoader;
use Ecommerce121\CategoryTableView\Model\Source\Eav\Category\Attribute\ConfigInheritance;
use InvalidArgumentException;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;

class ConfigResolver
{
    /**
     * @var Locator
     */
    private $locator;

    /**
     * @var ConfigLoader
     */
    private $configLoader;

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @param Locator $locator
     * @param ConfigLoader $configLoader
     * @param JsonSerializer $jsonSerializer
     */
    public function __construct(Locator $locator, ConfigLoader $configLoader, JsonSerializer $jsonSerializer)
    {
        $this->locator = $locator;
        $this->configLoader = $configLoader;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @param CategoryInterface $category
     *
     * @return array
     */
    public function get(CategoryInterface $category): ?array
    {
        foreach ($this->configLoader->load($category) as $configRow) {
            ['table_view_mode_config_inheritance' => $inheritance, 'table_view_mode_config' => $config] = $configRow;
            switch (true) {
                case $inheritance === null:
                    return null;
                case (int)$inheritance === ConfigInheritance::USE_CUSTOM:
                    $config = $this->unserializeConfig((string)$config);
                    return array_column($config, 'attribute_code');
            }
        }

        return null;
    }

    /**
     * @param string $config
     *
     * @return array|null
     */
    private function unserializeConfig(string $config): ?array
    {
        try {
            return $this->jsonSerializer->unserialize($config);
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }
}
