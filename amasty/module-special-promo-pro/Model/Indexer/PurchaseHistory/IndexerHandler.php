<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Special Promotions Pro for Magento 2
 */

namespace Amasty\RulesPro\Model\Indexer\PurchaseHistory;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Indexer\SaveHandler\Batch;
use Magento\Framework\Indexer\SaveHandler\IndexerInterface;
use Magento\Framework\Indexer\ScopeResolver\IndexScopeResolver;
use Magento\Framework\Search\Request\Dimension;

class IndexerHandler implements IndexerInterface
{
    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var Batch
     */
    private $batch;

    /**
     * @var int
     */
    private $batchSize;

    /**
     * @var IndexScopeResolver
     */
    private $indexScopeResolver;

    /**
     * @var IndexStructure
     */
    private $indexStructure;

    /**
     * @var array
     */
    private $data;

    /**
     * @var bool
     */
    private $isIndexTableExists = false;

    public function __construct(
        ResourceConnection $resource,
        Batch $batch,
        IndexScopeResolver $indexScopeResolver,
        IndexStructure $indexStructure,
        array $data,
        $batchSize = 50000
    ) {
        $this->resource = $resource;
        $this->batch = $batch;
        $this->indexScopeResolver = $indexScopeResolver;
        $this->indexStructure = $indexStructure;
        $this->data = $data;
        $this->batchSize = $batchSize;
    }

    public function saveIndex($dimensions, \Traversable $documents)
    {
        $this->checkIndexTable($dimensions);
        foreach ($this->batch->getItems($documents, $this->batchSize) as $batchDocuments) {
            if (!empty($batchDocuments)) {
                $this->resource->getConnection()
                    ->insertMultiple(
                        $this->getIndexTableName($dimensions),
                        array_values($batchDocuments)
                    );
            }
        }
    }

    public function deleteIndex($dimensions, \Traversable $documents)
    {
        $this->checkIndexTable($dimensions);
        foreach ($this->batch->getItems($documents, $this->batchSize) as $batchDocuments) {
            $this->resource->getConnection()
                ->delete(
                    $this->getIndexTableName($dimensions),
                    [IndexStructure::CUSTOMER_ID . ' in (?)' => $batchDocuments]
                );
        }
    }

    public function cleanIndex($dimensions)
    {
        $this->checkIndexTable($dimensions);
        $this->resource->getConnection()
            ->truncateTable($this->getIndexTableName($dimensions));
    }

    public function isAvailable($dimensions = []): bool
    {
        return true;
    }

    private function getIndexName(): string
    {
        return $this->data['indexer_id'];
    }

    /**
     * @param Dimension[] $dimensions
     * @return string
     */
    private function getIndexTableName(array $dimensions): string
    {
        return $this->indexScopeResolver->resolve($this->getIndexName(), $dimensions);
    }

    /**
     * @param Dimension[] $dimensions
     * @return void
     */
    private function checkIndexTable(array $dimensions): void
    {
        if (!$this->isIndexTableExists) {
            $tableName = $this->getIndexTableName($dimensions);
            if (!$this->resource->getConnection()->isTableExists($tableName)) {
                $this->indexStructure->create($this->getIndexName(), [], $dimensions);
            }
            $this->isIndexTableExists = true;
        }
    }
}
