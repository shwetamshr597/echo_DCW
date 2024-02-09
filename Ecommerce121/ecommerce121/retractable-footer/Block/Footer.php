<?php
/**
 * Copyright (c) 2021 121Ecommerce (https://www.121ecommerce.com/)
 */
declare(strict_types=1);

namespace Ecommerce121\FixedFooter\Block;

use Exception;
use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Ecommerce121\FixedFooter\Model\Config;

class Footer extends Template
{
    /**
     * @var CollectionFactory
     */
    protected $categoryCollection;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var AdapterFactory
     */
    private $imageFactory;

    /**
     * @var Config
     */
    protected $fixedFooterConfig;
    
    /**
     * Footer constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param CollectionFactory $categoryCollection
     * @param Filesystem $filesystem
     * @param AdapterFactory $imageFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        CollectionFactory $categoryCollection,
        Filesystem $filesystem,
        AdapterFactory $imageFactory,
        Config $fixedFooterConfig,
        array $data = []
    ) {
        $this->categoryCollection = $categoryCollection;
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
        $this->imageFactory = $imageFactory;
        $this->fixedFooterConfig = $fixedFooterConfig;

        parent::__construct($context, $data);
    }

    /**
     * @return Collection
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */

    public function getCategoryCollection(): Collection
    {
        $collection = $this->categoryCollection->create()
            ->addAttributeToSelect('*')
            ->setStore($this->storeManager->getStore())
            ->addAttributeToFilter('is_active', '1')
            ->addAttributeToFilter('category_display_in_footer', '1')
            ->addUrlRewriteToResult();
        if ($field = $this->fixedFooterConfig->getOrderField()) {
            $collection->setOrder($field);
        }

        return $collection;
    }

    /**
     * @param $src
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getResizedImage(
        $src,
        int $width = 80,
        int $height = 80
    ): string {
        try {
            $absPath = $this->filesystem
                ->getDirectoryRead(DirectoryList::PUB)
                ->getAbsolutePath($src);

            $imageResize = $this->imageFactory->create();
            $imageResize->open($absPath);
            $imageResize->backgroundColor([255, 255, 255]);
            $imageResize->constrainOnly(true);
            $imageResize->keepTransparency(true);
            $imageResize->keepFrame(true);
            $imageResize->keepAspectRatio(true);
            $imageResize->resize($width, $height);
            $imageResize->save($absPath);

        } catch (Exception $e) {
            $this->_logger->critical("Couldn't resize category image. " . $e->getMessage());
        }

        return $src;
    }
}
