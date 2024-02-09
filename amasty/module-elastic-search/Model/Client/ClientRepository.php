<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Elastic Search Base for Magento 2
 */

namespace Amasty\ElasticSearch\Model\Client;

use Amasty\ElasticSearch\Model\Client\ElasticsearchFactory;
use Amasty\Xsearch\Model\Config;

class ClientRepository implements ClientRepositoryInterface
{
    /**
     * @var Elasticsearch
     */
    private $client;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var ElasticsearchFactory
     */
    private $elasticsearchFactory;

    public function __construct(
        Config $config,
        ElasticsearchFactory $elasticsearchFactory
    ) {
        $this->config = $config;
        $this->elasticsearchFactory = $elasticsearchFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        if ($this->client == null) {
            $this->client = $this->elasticsearchFactory->create(['options' => $this->config->getConnectionData()]);
        }

        return $this->client;
    }
}
