<?php
/**
 * Copyright (c) 2021 121Ecommerce (https://www.121ecommerce.com/)
 */
declare(strict_types=1);

namespace Ecommerce121\FixedFooter\Model\Config\Backend;

use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

class Custom extends AbstractBackend
{
    /**
     * The maximum allowed number of categories for footer.
     */
    const MAX_CATEGORIES_FOOTER = 5;

    /**
     * Category collection factory
     *
     * @var CollectionFactory
     */
    protected $categoryCollection;

    /**
     * Store model manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     * @param CollectionFactory $categoryCollection
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        CollectionFactory $categoryCollection
    ) {
        $this->categoryCollection = $categoryCollection;
        $this->storeManager = $storeManager;
    }

    /**
     * Retrieve categories with enabled footer
     *
     * @return Collection
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCategoriesWithFooter()
    {
        return $this->categoryCollection->create()
            ->addAttributeToSelect('*')
            ->setStore($this->storeManager->getStore())
            ->addAttributeToFilter('is_active', '1')
            ->addAttributeToFilter('category_display_in_footer', '1');
    }

    /**
     * Category validation
     *
     * @param DataObject $object
     * @return bool
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function validate($object): bool
    {
        $attribute = $this->getAttribute();
        $attrCode = $attribute->getAttributeCode();
        $value = $object->getData($attrCode);
        $categories = $this->getCategoriesWithFooter();
        $categoriesCount = $categories->count();
        $currentCategoryId  = $object->getData("entity_id");
        $isUpdatable = false;

        if ($value === '1' && $categoriesCount === self::MAX_CATEGORIES_FOOTER) {
            /* If categories with footer are full and save on of them.
            Array with max 5 elements */
            foreach ($categories->getItems() as $category) {
                if ($category->getId() === $currentCategoryId) {
                    $isUpdatable = true;
                }
            }
            if ($isUpdatable === false) {
                throw new LocalizedException(
                    __(
                        'You can only have '
                        . self::MAX_CATEGORIES_FOOTER
                        . ' categories in the footer.'
                    )
                );
            }
        }

        return true;
    }
}
