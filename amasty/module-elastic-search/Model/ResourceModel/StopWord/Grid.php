<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Elastic Search Base for Magento 2
 */

namespace Amasty\ElasticSearch\Model\ResourceModel\StopWord;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Amasty\ElasticSearch\Api\Data\StopWordInterface;
use Amasty\ElasticSearch\Model\StopWord;
use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Grid extends SearchResult
{
    /**
     * @var string
     */
    protected $document = Stopword::class;

    //phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable = StopWordInterface::TABLE_NAME,
        $resourceModel = \Amasty\ElasticSearch\Model\ResourceModel\StopWord::class
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);
    }
}
