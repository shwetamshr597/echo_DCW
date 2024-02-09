<?php

declare(strict_types=1);

namespace Ecommerce121\CategoryTableView\Observer;

use InvalidArgumentException;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;

class PrepareTableViewModeAttributesObserver implements ObserverInterface
{
    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @param JsonSerializer $jsonSerializer
     */
    public function __construct(JsonSerializer $jsonSerializer)
    {
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @param EventObserver $observer
     *
     * @return void
     */
    public function execute(EventObserver $observer): void
    {
        $category = $observer->getEvent()->getData('category');
        $request = $observer->getEvent()->getData('request');
        if ($category instanceof CategoryInterface && $request instanceof RequestInterface) {
            $this->prepareConfigInheritance($category, $request);
            $this->prepareConfig($category, $request);
        }
    }

    /**
     * @param CategoryInterface $category
     * @param RequestInterface $request
     *
     * @return void
     */
    private function prepareConfigInheritance(CategoryInterface $category, RequestInterface $request): void
    {
        $categoryPostData = $request->getPostValue();
        if (is_array($categoryPostData)) {
            $value = null;
            $useConfig = (string)($categoryPostData['use_config']['table_view_mode_config_inheritance'] ?? '');
            if (!$this->convertStringToBool($useConfig)) {
                $value = $categoryPostData['table_view_mode_config_inheritance'] ?? null;
            }

            $category->setData('table_view_mode_config_inheritance', $value);
        }
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    private function convertStringToBool(string $value): bool
    {
        return $value === 'true';
    }

    /**
     * @param CategoryInterface $category
     * @param RequestInterface $request
     *
     * @return void
     */
    private function prepareConfig(CategoryInterface $category, RequestInterface $request): void
    {
        $categoryPostData = $request->getPostValue();
        if (is_array($categoryPostData)) {
            $value = null;
            if (array_key_exists('table_view_mode_config', $categoryPostData)
                && is_array($categoryPostData['table_view_mode_config'])
                && !empty($categoryPostData['table_view_mode_config'])
            ) {
                $config = [];
                foreach ($categoryPostData['table_view_mode_config'] as $attribute) {
                    $attributeCode = $attribute['attribute_code'] ?? null;
                    if (!$attributeCode) {
                        continue;
                    }

                    $config[] = ['attribute_code' => $attributeCode];
                }

                try {
                    $value = $this->jsonSerializer->serialize($config);
                } catch (InvalidArgumentException $e) {
                    $value = null;
                }
            }

            $category->setData('table_view_mode_config', $value);
        }
    }
}
