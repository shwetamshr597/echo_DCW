<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Elastic Search Base for Magento 2
 */

namespace Amasty\ElasticSearch\Model\Indexer\Structure\AnalyzerBuilder;

use Amasty\ElasticSearch\Api\Data\Indexer\Structure\AnalyzerBuilderInterface;
use Amasty\ElasticSearch\Model\Indexer\Structure\AnalyzerBuilder\EntityCollectionProvider;

class Smartcn implements AnalyzerBuilderInterface
{
    /**
     * @var EntityCollectionProvider
     */
    private $entityCollectionProvider;

    public function __construct(EntityCollectionProvider $entityCollectionProvider)
    {
        $this->entityCollectionProvider = $entityCollectionProvider;
    }

    /**
     * @param int $storeId
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function build($storeId)
    {
        $analyser = [
            'analyzer' => [
                'default' => [
                    'type'      => 'custom',
                    'tokenizer' => 'smartcn_tokenizer',
                    'filter'    => [
                        'stop_filter',
                        "graph_synonyms",
                        'lowercase'
                    ],
                ]
            ],
            'filter'   => [
                'stop_filter' => [
                    "type" => "stop",
                    "stopwords" => $this->getStopWords($storeId)
                ],
                "graph_synonyms" => [
                    "type" => "synonym_graph",
                    "lenient" => true,
                    "synonyms" => $this->getSynonyms($storeId)
                ]
            ],
        ];

        return $analyser;
    }

    /**
     * @param $storeId
     * @return array|string
     */
    private function getStopWords($storeId)
    {
        $stopWords = [];
        $collection = $this->entityCollectionProvider->getStopWordCollectionFactory()->create();
        $collection->addStoreFilter($storeId);

        foreach ($collection as $stopWord) {
            $stopWords[] = preg_replace('/\s*/u', '-', $stopWord->getTerm());
        }

        if (!count($stopWords)) {
            $stopWords = '_none_';
        }

        return $stopWords;
    }

    /**
     * @param $storeId
     * @return array
     */
    private function getSynonyms($storeId)
    {
        $synonyms = [];
        $collection = $this->entityCollectionProvider->getSynonymCollectionFactory()->create();
        $collection->addStoreFilter($storeId);
        foreach ($collection as $synonym) {
            $synonyms[] = trim($synonym->getTerm());
        }

        return $synonyms ?: ['']; //can't pass empty array to elastic 5.x
    }
}
