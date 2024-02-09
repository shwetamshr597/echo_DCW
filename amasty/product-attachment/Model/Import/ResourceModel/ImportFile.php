<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Product Attachments Base for Magento 2
 */

namespace Amasty\ProductAttachment\Model\Import\ResourceModel;

use Amasty\ProductAttachment\Model\Import\ImportFile as ImportFileModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ImportFile extends AbstractDb
{
    public const TABLE_NAME = 'amasty_file_import_file';

    public function _construct()
    {
        $this->_init(self::TABLE_NAME, ImportFileModel::IMPORT_FILE_ID);
    }

    /**
     * @param int $importId
     * @param array $importFileIds
     */
    public function deleteFiles($importId, $importFileIds)
    {
        $this->getConnection()->delete(
            $this->getMainTable(),
            [
                ImportFileModel::IMPORT_FILE_ID . ' IN (?)' => array_unique($importFileIds),
                ImportFileModel::IMPORT_ID => (int)$importId
            ]
        );
    }
}
