<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\ViewModel;

use Ecommerce121\CategoryTableView\Model\Category\GetFilters;
use Ecommerce121\CategoryTableView\Model\Config;
use Ecommerce121\CategoryTableView\Model\Eav\PriceAttributeValidator;
use InvalidArgumentException;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Data\CollectionFactory;
use Magento\Framework\Pricing\Helper\Data as PriceFormatter;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class TableView implements ArgumentInterface
{
    private const PRICE_ATTRIBUTE_CODE = ProductInterface::PRICE;
    private const PRICE_COLUMN_CODE = 'price-wrapper';
    private const PRICE_DATA_ATTRIBUTE = 'data-price-amount';

    private const ATTRIBUTE_VALUE_SEPARATOR = ', ';

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var GetFilters
     */
    private $getFilters;

    /**
     * @var PriceFormatter
     */
    private $priceFormatter;

    /**
     * @var PriceAttributeValidator
     */
    private $priceAttributeValidator;

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    private $attributeList;

    /**
     * @param CollectionFactory $collectionFactory
     * @param GetFilters $getFilters
     * @param PriceFormatter $priceFormatter
     * @param PriceAttributeValidator $priceAttributeValidator
     * @param JsonSerializer $jsonSerializer
     * @param Config $config
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        GetFilters $getFilters,
        PriceFormatter $priceFormatter,
        PriceAttributeValidator $priceAttributeValidator,
        JsonSerializer $jsonSerializer,
        Config $config
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->getFilters = $getFilters;
        $this->priceFormatter = $priceFormatter;
        $this->priceAttributeValidator = $priceAttributeValidator;
        $this->jsonSerializer = $jsonSerializer;
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getAttributeList(): array
    {
        if ($this->attributeList === null) {
            $this->attributeList = $this->getFilters->execute();
        }

        return $this->attributeList;
    }

    /**
     * @param string $attributeCode
     *
     * @return string
     */
    public function getColumnCode(string $attributeCode): string
    {
        if ($this->isMainPriceAttribute($attributeCode)) {
            return self::PRICE_COLUMN_CODE;
        }

        return $attributeCode;
    }

    /**
     * @param string $attributeCode
     *
     * @return bool
     */
    public function isMainPriceAttribute(string $attributeCode): bool
    {
        return $attributeCode === self::PRICE_ATTRIBUTE_CODE;
    }

    /**
     * @param string $attributeCode
     *
     * @return bool
     */
    public function isPriceAttribute(string $attributeCode): bool
    {
        return $this->priceAttributeValidator->isPriceAttribute($attributeCode);
    }

    /**
     * @param Product $product
     * @param string $attributeCode
     *
     * @return string
     */
    public function getAttributeValue(Product $product, string $attributeCode): string
    {
        $value = $product->getAttributeText($attributeCode);
        if ($value === false) {
            return (string)$product->getData($attributeCode);
        }

        if (is_array($value)) {
            return join(self::ATTRIBUTE_VALUE_SEPARATOR, $value);
        }

        return (string)$value;
    }

    /**
     * @param Product $product
     * @param string $attributeCode
     *
     * @return string
     */
    public function getPriceValue(Product $product, string $attributeCode): string
    {
        if (!$product->getData($attributeCode)) {
            return '';
        }

        return $this->priceFormatter->currency($product->getData($attributeCode), true, false);
    }

    /**
     * @return string
     */
    public function getSortableListConfig(): string
    {
        $config = [];
        foreach (array_keys($this->attributeList) as $attributeCode) {
            if ($this->isMainPriceAttribute($attributeCode)) {
                continue;
            }

            $config[] = $attributeCode;
        }

        $config[] = [
            'name' => self::PRICE_COLUMN_CODE,
            'attr' => self::PRICE_DATA_ATTRIBUTE
        ];

        try {
            $config = $this->jsonSerializer->serialize($config);
        } catch (InvalidArgumentException $e) {
            $config = '';
        }

        return $config;
    }

    /**
     * @return bool
     */
    public function canShowDescription(): bool
    {
        return $this->config->canShowShortDescription();
    }
}
