<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Amasty Improved Sorting GraphQl for Magento 2 (System)
*/

declare(strict_types=1);

namespace Amasty\SortingGraphQl\Plugin\Sorting\Model\IsSearchPage;

use Amasty\Sorting\Model\IsSearchPage;
use Amasty\SortingGraphQl\Model\SearchPageFlag;

class DetectSearchPage
{
    /**
     * @var SearchPageFlag
     */
    private $searchPageFlag;

    public function __construct(SearchPageFlag $searchPageFlag)
    {
        $this->searchPageFlag = $searchPageFlag;
    }

    /**
     * @param IsSearchPage $subject
     * @return bool
     */
    public function aroundExecute(IsSearchPage $subject): bool
    {
        return $this->searchPageFlag->get();
    }
}
