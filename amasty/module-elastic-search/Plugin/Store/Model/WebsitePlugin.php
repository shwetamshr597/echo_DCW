<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Elastic Search Base for Magento 2
 */

namespace Amasty\ElasticSearch\Plugin\Store\Model;

use Amasty\ElasticSearch\Model\Indexer\RelevanceRule\RuleProductProcessor;
use Magento\Store\Model\Website;

class WebsitePlugin
{
    /**
     * @var RuleProductProcessor
     */
    private $ruleProductProcessor;

    public function __construct(RuleProductProcessor $ruleProductProcessor)
    {
        $this->ruleProductProcessor = $ruleProductProcessor;
    }

    /**
     * @param Website $subject
     * @param Website $result
     * @return Website
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterDelete(Website $subject, Website $result)
    {
        $this->ruleProductProcessor->markIndexerAsInvalid();
        return $result;
    }
}
