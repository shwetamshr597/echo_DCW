<?php
namespace Ecommerce121\Core\Plugin\View\Element;

/**
 * Class AbstractBlock
 * @package Ecommerce121\Core\Plugin\View\Element
 */
class AbstractBlock
{
    public function aroundEscapeHtml(
    	$subject,
    	callable $proceed,
    	$data,
    	$allowedTags = null
    ) {
    	if (!empty($data) && !is_array($data) && strpos($data, '<span class="ecommerce121-logo">121Ecommerce</span>') !== false) {
    		$result = $data;
    	} else {
    		$result = $proceed($data, $allowedTags);
    	}
        return $result;
    }
}
