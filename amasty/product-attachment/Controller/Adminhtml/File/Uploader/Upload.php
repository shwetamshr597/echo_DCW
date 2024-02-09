<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Attachments Base for Magento 2
 */

namespace Amasty\ProductAttachment\Controller\Adminhtml\File\Uploader;

use Amasty\ProductAttachment\Controller\Adminhtml\RegistryConstants;
use Magento\Framework\Controller\ResultFactory;

class Upload extends \Amasty\ProductAttachment\Controller\Adminhtml\File
{
    /**
     * @var \Amasty\ProductAttachment\Model\Filesystem\FileUploader
     */
    private $fileUploader;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Amasty\ProductAttachment\Model\Filesystem\FileUploader $fileUploader
    ) {
        parent::__construct($context);
        $this->fileUploader = $fileUploader;
    }

    /**
     * Upload file controller action.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        return $resultJson->setData($this->fileUploader->uploadFile(RegistryConstants::FILE_KEY));
    }
}
