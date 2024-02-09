<?php

namespace Ecommerce121\Core\Model;

use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\ResourceConnection;

class AttributeIdsResolver
{
    const TABLE_ATTRIBUTE = 'eav_attribute';

    const COLUMN_ATTRIBUTE_ID = 'attribute_id';
    const COLUMN_ATTRIBUTE_CODE = 'attribute_code';
    const COLUMN_ENTITY_TYPE_ID = 'entity_type_id';

    /**
     * @var EavConfig
     */
    private $eavConfig;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var string
     */
    private $entityTypeCode;

    /**
     * @var string[]
     */
    private $attributeCodes;

    /**
     * @param EavConfig $eavConfig
     * @param ResourceConnection $resourceConnection
     * @param string $entityTypeCode
     * @param string[] $attributeCodes
     */
    public function __construct(
        EavConfig $eavConfig,
        ResourceConnection $resourceConnection,
        $entityTypeCode,
        array $attributeCodes = []
    ) {
        $this->eavConfig = $eavConfig;
        $this->resourceConnection = $resourceConnection;
        $this->entityTypeCode = $entityTypeCode;
        $this->attributeCodes = $attributeCodes;
    }

    /**
     * @return int[]
     */
    public function resolve()
    {
        if (empty($this->attributeCodes)) {
            return [];
        }

        try {
            $ids = $this->getConnection()->fetchCol($this->buildSelect());
            $ids = array_filter($ids);
            $ids = array_map('intval', $ids);
        } catch (LocalizedException $e) {
            $ids = [];
        }

        return $ids;
    }

    /**
     * @return Select
     * @throws LocalizedException
     */
    private function buildSelect()
    {
        $select = $this->getConnection()->select();
        $select
            ->from($this->getConnection()->getTableName(self::TABLE_ATTRIBUTE), [self::COLUMN_ATTRIBUTE_ID])
            ->where(sprintf('%s = ?', self::COLUMN_ENTITY_TYPE_ID), $this->getEntityTypeId())
            ->where(sprintf('%s IN (?)', self::COLUMN_ATTRIBUTE_CODE), $this->attributeCodes);

        return $select;
    }

    /**
     * @return int
     * @throws LocalizedException
     */
    private function getEntityTypeId()
    {
        return (int)$this->eavConfig->getEntityType($this->entityTypeCode)->getId();
    }

    /**
     * @return AdapterInterface
     */
    private function getConnection()
    {
        return $this->resourceConnection->getConnection();
    }
}
