<?php
declare(strict_types=1);

namespace DCW\ProductImport\Controller\Adminhtml\Import;

use Magento\ImportExport\Model\Import\Adapter;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Product Import
 *
 */
class Product extends \Magento\Backend\App\Action
{
 /**
     * Import field separator.
     */
    const FIELD_FIELD_SEPARATOR = '_import_field_separator';

    /**
     * @var \DCW\ProductImport\Model\Import\Product
     */
    protected $productImport;

    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    protected $directoryManager;

    /**
     * @var \Magento\ImportExport\Model\Import $import
     */
    protected $import;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \DCW\ProductImport\Model\Import\Product $productImport
     * @param \Magento\Framework\Filesystem\DirectoryList $directoryManager
     * @param \Magento\ImportExport\Model\Import $import
     * @param Filesystem $filesystem
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \DCW\ProductImport\Model\Import\Product $productImport,
        \Magento\Framework\Filesystem\DirectoryList $directoryManager,
        \Magento\ImportExport\Model\Import $import,
        Filesystem $filesystem,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->productImport = $productImport;
        $this->directoryManager = $directoryManager;
        $this->import = $import;
        $this->filesystem = $filesystem;
        $this->logger = $logger;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $varDirectory = $this->directoryManager->getPath('pub');
        $productCsvFileName = "catalog_product";
        $file = $varDirectory . DIRECTORY_SEPARATOR .'productimport/catalog_product.csv';
        $resultJson = $this->resultJsonFactory->create();
        $file = fopen($varDirectory . DIRECTORY_SEPARATOR .'productimport/catalog_product.csv', 'r', true); // 
        $stockRegistry = $objectManager->get('Magento\CatalogInventory\Api\StockRegistryInterface');
        $header = fgetcsv($file); // get data headers and skip 1st row
        $required_data_fields = 3;
        while ($row = fgetcsv($file, 3000, ",") )
        {
            $data_count = count($row);
            if ($data_count < 1)
            {
                continue;
            }
            $product = $objectManager->create('Magento\Catalog\Model\Product');         
            $data = array();
            $data = array_combine($header, $row);
        
            $sku = $data['sku'];
            if ($data_count < $required_data_fields)
            {
                echo("Skipping product sku " . $sku . ", not all required fields are present to create the product.");
                continue;
            }
        
            $name = $data['name'];
            $description = $data['description'];
            $shortDescription = $data['short_description'];
            $qty = trim($data['qty']);
            $price = trim($data['price']);
        
            try
            {
                $product->setTypeId('simple') // product type
                        ->setStatus(1) // 1 = enabled
                        ->setAttributeSetId(4)
                        ->setName($name)
                        ->setSku($sku)
                        ->setPrice($price)
                        ->setTaxClassId(0) // 0 = None
                        ->setCategoryIds(array(2, 3)) // array of category IDs, 2 = Default Category
                        ->setDescription($description)
                        ->setShortDescription($shortDescription)
                        ->setWebsiteIds(array(1)) // Default Website ID
                        ->setStoreId(0) // Default store ID
                        ->setVisibility(4) 
                        ->setMetaTitle($data['meta_title']) 
                        ->setWeight($data['weight']) 
                        ->setMetaKeywords($data['meta_keywords']) 
                        ->setMetaDescription($data['meta_description']) // 4 = Catalog & Search
                        ->save();
        
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();
                continue;
            }
            try
            {
                $stockItem = $stockRegistry->getStockItemBySku($sku);
        
                if ($stockItem->getQty() != $qty)
                {
                    $stockItem->setQty($qty);
                    if ($qty > 0)
                    {
                        $stockItem->setIsInStock(1);
                    }
                    $stockRegistry->updateStockItemBySku($sku, $stockItem);
                }
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();
            }
            // unset($product);
        }
        fclose($file);
        return $resultJson->setData(['success' => true]);    
}

}