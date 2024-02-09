<?php

namespace Ecommerce121\Core\Block\Adminhtml\Attribute;

use Ecommerce121\Core\Model\AttributeIdsResolver;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;

class Logo extends Template
{
    /**
     * 121Ecommerce LLC logo file.
     */
    const LOGO_FILE = 'Ecommerce121_Core::images/logo121.svg';

    /**
     * CSS Selector pattern to add logo to the attributes.
     */
    const SELECTOR_PATTERN = 'tr[title*="attribute_id/%d"] .col-frontend_label';

    /**
     * @var AttributeIdsResolver
     */
    private $attributeIdsResolver;

    /**
     * @param AttributeIdsResolver $attributeIdsResolver
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        AttributeIdsResolver $attributeIdsResolver,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->attributeIdsResolver = $attributeIdsResolver;
    }

    /**
     * @return string[]
     */
    public function getSelectors()
    {
        $selectors = [];
        foreach ($this->getAttributeIds() as $attributeId) {
            $selectors[] = sprintf(self::SELECTOR_PATTERN, $attributeId);
        }

        return $selectors;
    }

    /**
     * @return int[]
     */
    public function getAttributeIds()
    {
        return $this->attributeIdsResolver->resolve();
    }

    /**
     * @return string
     */
    public function getLogoUrl()
    {
        return $this->getViewFileUrl(self::LOGO_FILE);
    }
}
