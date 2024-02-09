<?php
declare(strict_types=1);

namespace DCW\ProductImport\Model\Import;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\ImportExport\Model\Import;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\ImportExport\Model\Import\AbstractSource;
use Magento\Customer\Model\Indexer\Processor;
use Magento\Framework\App\ObjectManager;

/**
 * Product Import
 *
 */
class Product
{

    /**
     * @var \Magento\ImportExport\Model\Import
     */
    protected $import;

    /**
     * @var \Magento\ImportExport\Model\Import\ImageDirectoryBaseProvider
     */
    protected $imagesDirProvider;

    /**
     * @param \Magento\ImportExport\Model\Import $import
     * @param \Magento\ImportExport\Model\Import\ImageDirectoryBaseProvider $imageDirectoryBaseProvider
     */
    public function __construct(
        \Magento\ImportExport\Model\Import $import,
        \Magento\ImportExport\Model\Import\ImageDirectoryBaseProvider $imageDirectoryBaseProvider
    ) {
        $this->import = $import;
        $this->imagesDirProvider = $imageDirectoryBaseProvider;
    }

    public function startImport($imagePath)
    {
        $data = [
            'entity' => 'catalog_product',
            'behavior' => 'append',
            'validation_strategy' => 'validation-stop-on-errors',
            'allowed_error_count' => 1,
            '_import_field_separator' => '',
            '_import_multiple_value_separator' => '',
            '_import_empty_attribute_value_constant' => '__EMPTY__VALUE__',
            'import_images_file_dir' => ''

        ];
        $this->import->setData($data);
        //Images can be read only from given directory.
        $this->import->setData('images_base_directory', $this->imagesDirProvider->getDirectory());
        $errorAggregator = $this->import->getErrorAggregator();
        $errorAggregator->initValidationStrategy(
            $this->import->getData(Import::FIELD_NAME_VALIDATION_STRATEGY),
            $this->import->getData(Import::FIELD_NAME_ALLOWED_ERROR_COUNT)
        );
        try {
            $this->import->importSource();
        } catch (\Exception $e) {
            echo ('Something went wrong while importing products');
            echo($e->getMessage());
        }
    }
}