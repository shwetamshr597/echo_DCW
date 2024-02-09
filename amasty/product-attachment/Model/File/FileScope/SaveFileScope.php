<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Attachments Base for Magento 2
 */

namespace Amasty\ProductAttachment\Model\File\FileScope;

class SaveFileScope implements SaveFileScopeInterface
{
    /**
     * @var SaveProcessors\FileScopeSaveProcessorInterface[]
     */
    private $saveProcessors;

    public function __construct(
        $saveProcessors
    ) {
        $this->saveProcessors = $saveProcessors;
    }

    /**
     * @inheritdoc
     */
    public function execute($params, $saveProcessorName)
    {
        if (!isset($this->saveProcessors[$saveProcessorName])) {
            throw new \Amasty\ProductAttachment\Exceptions\NoSuchSaveProcessorException();
        }

        return $this->saveProcessors[$saveProcessorName]->execute($params);
    }
}
