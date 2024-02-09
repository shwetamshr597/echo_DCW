<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Elastic Search Base for Magento 2
 */

namespace Amasty\ElasticSearch\Setup\Patch\Schema;

use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @see \Amasty\Base\Plugin\Framework\Setup\Declaration\Schema\Diff\Diff\RestrictDropTables
 */
class DropOldIndexTable implements SchemaPatchInterface
{
    public const OLD_INDEX_TABLE = 'amasty_elastic_relevance_rule_index';

    /**
     * @var SchemaSetupInterface
     */
    private $schemaSetup;

    public function __construct(
        SchemaSetupInterface $schemaSetup
    ) {
        $this->schemaSetup = $schemaSetup;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): DropOldIndexTable
    {
        $connection = $this->schemaSetup->getConnection();
        $oldTableName = $this->schemaSetup->getTable(self::OLD_INDEX_TABLE);

        if ($connection->isTableExists($oldTableName)) {
            $connection->dropTable($oldTableName);
        }

        return $this;
    }
}
