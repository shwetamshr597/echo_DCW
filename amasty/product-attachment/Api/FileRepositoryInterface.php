<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Attachments Base for Magento 2
 */

namespace Amasty\ProductAttachment\Api;

interface FileRepositoryInterface
{
    /**
     * Save file.
     *
     * @param \Amasty\ProductAttachment\Api\Data\FileInterface $file
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return \Amasty\ProductAttachment\Api\Data\FileInterface
     */
    public function save(\Amasty\ProductAttachment\Api\Data\FileInterface $file);

    /**
     * Save file with relations.
     *
     * @param \Amasty\ProductAttachment\Api\Data\FileInterface $file
     * @param array $params
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return \Amasty\ProductAttachment\Api\Data\FileInterface
     */
    public function saveAll(\Amasty\ProductAttachment\Api\Data\FileInterface $file, $params = []);

    /**
     * Retrieve file.
     *
     * @param int $fileId
     * @return \Amasty\ProductAttachment\Api\Data\FileInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($fileId);

    /**
     * Retrieve file by Url Hash.
     *
     * @param string $hash
     * @return \Amasty\ProductAttachment\Api\Data\FileInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByHash($hash);

    /**
     * Delete file.
     *
     * @param \Amasty\ProductAttachment\Api\Data\FileInterface $file
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Amasty\ProductAttachment\Api\Data\FileInterface $file);

    /**
     * Delete file by ID.
     *
     * @param int $fileId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($fileId);
}
