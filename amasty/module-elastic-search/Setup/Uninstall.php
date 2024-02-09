<?php

declare(strict_types=1);

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Elastic Search Base for Magento 2
 */

namespace Amasty\ElasticSearch\Setup;

use Amasty\ElasticSearch\Api\Data\RelevanceRuleInterface;
use Amasty\ElasticSearch\Api\Data\StopWordInterface;
use Amasty\ElasticSearch\Api\Data\SynonymInterface;
use Amasty\ElasticSearch\Model\Indexer\RelevanceRule\IndexBuilder;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;

class Uninstall implements UninstallInterface
{
    public const TABLES_TO_DELETE = [
        RelevanceRuleInterface::TABLE_NAME,
        IndexBuilder::TABLE_NAME,
        SynonymInterface::TABLE_NAME,
        StopWordInterface::TABLE_NAME
    ];

    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $setup->startSetup();

        foreach (self::TABLES_TO_DELETE as $table) {
            $setup->getConnection()->dropTable($setup->getTable($table));
        }

        $setup->endSetup();
    }
}
