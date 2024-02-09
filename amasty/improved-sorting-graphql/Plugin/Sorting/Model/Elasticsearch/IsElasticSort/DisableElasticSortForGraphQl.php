<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Amasty Improved Sorting GraphQl for Magento 2 (System)
*/

declare(strict_types=1);

namespace Amasty\SortingGraphQl\Plugin\Sorting\Model\Elasticsearch\IsElasticSort;

use Amasty\Sorting\Model\Elasticsearch\IsElasticSort;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;

/**
 * Disabling elastic sorting for graphql, since a collection is created in the graphql area and plugins
 * for the front-end collection do not work, which is why elastic sorting does not work and need to apply mysql sorting.
 */
class DisableElasticSortForGraphQl
{
    /**
     * @var State
     */
    private $state;

    public function __construct(
        State $state
    ) {
        $this->state = $state;
    }

    /**
     * @see IsElasticSort::execute()
     */
    public function aroundExecute(IsElasticSort $subject, callable $proceed): bool
    {
        if ($this->state->getAreaCode() === Area::AREA_GRAPHQL) {
            return false;
        }

        return $proceed();
    }
}
