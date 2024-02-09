<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\Model\ResourceModel\Category;

use Exception;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Eav\Api\AttributeRepositoryInterface as AttributeRepository;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Exception\NoSuchEntityException;
use Zend_Db_Expr as Expression;
use Zend_Db_ExprFactory as ExpressionFactory;
use Magento\Framework\EntityManager\MetadataPool;

class ConfigLoader
{
    private const DEFAULT_LINK_FIELD = 'entity_id';

    /**
     * @var AttributeRepository
     */
    private $attributeRepository;

    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @var ExpressionFactory
     */
    private $expressionFactory;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var AttributeInterface[]
     */
    private $attributeList = [];

    /**
     * @param AttributeRepository $attributeRepository
     * @param MetadataPool $metadataPool
     * @param ExpressionFactory $expressionFactory
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        MetadataPool $metadataPool,
        ExpressionFactory $expressionFactory,
        ResourceConnection $resourceConnection
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->metadataPool = $metadataPool;
        $this->expressionFactory = $expressionFactory;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param CategoryInterface $category
     *
     * @return array
     */
    public function load(CategoryInterface $category): array
    {
        $path = array_map('intval', $category->getPathIds());
        $path = array_reverse($path);

        return $this->loadByPath($path);
    }

    /**
     * @param int[] $categoryPath
     *
     * @return array
     */
    public function loadByPath(array $categoryPath = []): array
    {
        try {
            $select = $this->buildSelect($categoryPath);
            $configList = $this->getConnection()->fetchAssoc($select);
        } catch (NoSuchEntityException $e) {
            return [];
        }

        return $configList;
    }

    /**
     * @param int[] $categoryPath
     *
     * @return Select
     * @throws NoSuchEntityException
     */
    private function buildSelect(array $categoryPath): Select
    {
        $select = $this->getConnection()->select();

        $select
            ->from(
                ['c' => $this->getConnection()->getTableName('catalog_category_entity')],
                ['category_id' => sprintf('c.%s', $this->getCategoryLinkField())]
            )
            ->joinLeft(
                [$this->getInheritanceAlias() => $this->getInheritanceTable()],
                join(
                    ' AND ',
                    [
                        sprintf(
                            '%1$s.%2$s = c.%2$s',
                            $this->getInheritanceAlias(),
                            $this->getCategoryLinkField()
                        ),
                        sprintf(
                            '%s.attribute_id = %d',
                            $this->getInheritanceAlias(),
                            (int)$this->getInheritanceAttribute()->getAttributeId()
                        ),
                    ]
                ),
                [$this->getInheritanceAttribute()->getAttributeCode() => $this->getInheritanceAlias(). '.value']
            )
            ->joinLeft(
                [$this->getConfigAlias() => $this->getConfigTable()],
                join(
                    ' AND ',
                    [
                        sprintf(
                            '%1$s.%2$s = c.%2$s',
                            $this->getConfigAlias(),
                            $this->getCategoryLinkField(),
                        ),
                        sprintf(
                            '%s.attribute_id = %d',
                            $this->getConfigAlias(),
                            (int)$this->getConfigAttribute()->getAttributeId()
                        ),
                    ]
                ),
                [$this->getConfigAttribute()->getAttributeCode() => $this->getConfigAlias(). '.value']
            )
            ->where(sprintf('c.%s IN (?)', $this->getCategoryLinkField()), $categoryPath)
            ->order($this->buildOrderExpression($categoryPath));

        return $select;
    }

    /**
     * @param array $categoryPath
     *
     * @return Expression
     */
    private function buildOrderExpression(array $categoryPath): Expression
    {
        return $this->expressionFactory->create(
            [
                'expression' => sprintf('FIELD (c.entity_id, %s)', implode(',', $categoryPath))
            ]
        );
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    private function getInheritanceAlias(): string
    {
        return $this->getAttributeAlias($this->getInheritanceAttribute());
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    private function getConfigAlias(): string
    {
        return $this->getAttributeAlias($this->getConfigAttribute());
    }

    /**
     * @param AttributeInterface $attribute
     *
     * @return string
     */
    private function getAttributeAlias(AttributeInterface $attribute): string
    {
        return 'at_' . $attribute->getAttributeCode();
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    private function getInheritanceTable(): string
    {
        return $this->getAttributeTable($this->getInheritanceAttribute());
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    private function getConfigTable(): string
    {
        return $this->getAttributeTable($this->getConfigAttribute());
    }

    /**
     * @param AttributeInterface $attribute
     *
     * @return string
     */
    private function getAttributeTable(AttributeInterface $attribute): string
    {
        return $attribute->getBackendTable();
    }

    /**
     * @return AttributeInterface
     * @throws NoSuchEntityException
     */
    public function getInheritanceAttribute(): AttributeInterface
    {
        return $this->getAttribute('table_view_mode_config_inheritance');
    }

    /**
     * @return AttributeInterface
     * @throws NoSuchEntityException
     */
    public function getConfigAttribute(): AttributeInterface
    {
        return $this->getAttribute('table_view_mode_config');
    }

    /**
     * @param string $attributeCode
     *
     * @return AttributeInterface
     * @throws NoSuchEntityException
     */
    private function getAttribute(string $attributeCode): AttributeInterface
    {
        if (!array_key_exists($attributeCode, $this->attributeList)) {
            $this->attributeList[$attributeCode] = $this->attributeRepository->get(Category::ENTITY, $attributeCode);
        }

        return $this->attributeList[$attributeCode];
    }

    /**
     * @return string
     */
    private function getCategoryLinkField(): string
    {
        try {
            $productLinkField = $this->metadataPool->getMetadata(CategoryInterface::class)->getLinkField();
        } catch (Exception $e) {
            $productLinkField = self::DEFAULT_LINK_FIELD;
        }

        return $productLinkField;
    }

    /**
     * @return AdapterInterface
     */
    private function getConnection(): AdapterInterface
    {
        return $this->resourceConnection->getConnection();
    }
}
