<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\Model\Eav;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Exception\LocalizedException;

class PriceAttributeValidator
{
    /**
     * @var EavConfig
     */
    private $eavConfig;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var string[]
     */
    private $priceAttributeList;

    /**
     * @param EavConfig $eavConfig
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        EavConfig $eavConfig,
        ResourceConnection $resourceConnection
    ) {
        $this->eavConfig = $eavConfig;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param string $attributeCode
     *
     * @return bool
     */
    public function isPriceAttribute(string $attributeCode): bool
    {
        return in_array($attributeCode, $this->getPriceAttributeCodes(), true);
    }

    /**
     * @return string[]
     */
    private function getPriceAttributeCodes(): array
    {
        if ($this->priceAttributeList === null) {
            try {
                $select = $this->getConnection()->select();
                $select
                    ->from($this->getConnection()->getTableName('eav_attribute'), ['attribute_code'])
                    ->where('entity_type_id = ?', $this->eavConfig->getEntityType(Product::ENTITY)->getId())
                    ->where('frontend_input = ?', 'price');

                $this->priceAttributeList = $this->getConnection()->fetchCol($select);
            } catch (LocalizedException $e) {
                $this->priceAttributeList = [];
            }
        }

        return $this->priceAttributeList;
    }

    /**
     * @return AdapterInterface
     */
    private function getConnection(): AdapterInterface
    {
        return $this->resourceConnection->getConnection();
    }
}
