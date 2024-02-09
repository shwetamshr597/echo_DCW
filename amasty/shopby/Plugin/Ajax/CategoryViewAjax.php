<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
 * @package Improved Layered Navigation Base for Magento 2
 */

namespace Amasty\Shopby\Plugin\Ajax;

use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\Page;

class CategoryViewAjax extends Ajax
{
    /**
     * @param Action $controller
     *
     * @return array
     */
    public function beforeExecute(Action $controller)
    {
        if ($this->isAjax($controller->getRequest())) {
            $this->getActionFlag()->set('', 'no-renderLayout', true);
        }

        return [];
    }

    /**
     * @param Action $controller
     * @param Page $page
     *
     * @return \Magento\Framework\Controller\Result\Raw|Page
     */
    public function afterExecute(Action $controller, $page)
    {
        if (!$this->isAjax($controller->getRequest())) {
            return $page;
        }

        $responseData = $this->getAjaxResponseData();
        $response = $this->prepareResponse($responseData);
        return $response;
    }
}
