<?php

namespace WeltPixel\GA4\Model\ServerSide\Events;

use WeltPixel\GA4\Api\ServerSide\Events\RefundInterface;
use WeltPixel\GA4\Api\ServerSide\Events\RefundInterfaceFactory;
use WeltPixel\GA4\Api\ServerSide\Events\RefundItemInterfaceFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use WeltPixel\GA4\Helper\ServerSideTracking as GA4Helper;
use WeltPixel\GA4\Model\Dimension as DimensionModel;
use Magento\Sales\Api\CreditmemoRepositoryInterface;

class RefundBuilder implements \WeltPixel\GA4\Api\ServerSide\Events\RefundBuilderInterface
{
    /**
     * @var RefundInterfaceFactory
     */
    protected $refundFactory;

    /**
     * @var RefundItemInterfaceFactory
     */
    protected $refundItemFactory;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var GA4Helper
     */
    protected $ga4Helper;

    /**
     * @var DimensionModel
     */
    protected $dimensionModel;

    /**
     * @var CreditmemoRepositoryInterface
     */
    protected $creditmemoRepository;

    /**
     * @param RefundInterfaceFactory $refundFactory
     * @param RefundItemInterfaceFactory $refundItemFactory
     * @param OrderRepositoryInterface $orderRepository
     * @param GA4Helper $ga4Helper
     * @param DimensionModel $dimensionModel
     * @param CreditmemoRepositoryInterface $creditmemoRepository
     */
    public function __construct(
        RefundInterfaceFactory $refundFactory,
        RefundItemInterfaceFactory $refundItemFactory,
        OrderRepositoryInterface $orderRepository,
        GA4Helper $ga4Helper,
        DimensionModel $dimensionModel,
        CreditmemoRepositoryInterface $creditmemoRepository
    )
    {
        $this->refundFactory = $refundFactory;
        $this->refundItemFactory = $refundItemFactory;
        $this->orderRepository = $orderRepository;
        $this->ga4Helper = $ga4Helper;
        $this->dimensionModel = $dimensionModel;
        $this->creditmemoRepository = $creditmemoRepository;
    }

    /**
     * @param \Magento\Sales\Model\Order\Creditmemo|int $creditmemo
     * @return null|RefundInterface
     */
    function getRefundEvent($creditmemo)
    {
        /** @var RefundInterface $refundEvent */
        $refundEvent = $this->refundFactory->create();

        if (!$creditmemo) {
            return $refundEvent;
        }

        if (is_int($creditmemo)) {
            try {
                $creditmemo = $this->creditmemoRepository->get($creditmemo);
            } catch (\Exception $ex)  {
                return $refundEvent;
            }
        }

        $order = $creditmemo->getOrder();

        $displayOption = $this->ga4Helper->getParentOrChildIdUsage();
        $currencyCode = $creditmemo->getOrderCurrencyCode();

        $clientId = $order->getData('ga_cookie');
        if ($order->getData('ga_session_id')) {
            $gaSessionId = $order->getData('ga_session_id');
        }
        if ($order->getData('ga_timestamp')) {
            $gaTimestamp = $order->getData('ga_timestamp');;
        }
        $userId = $order->getCustomerId();

        if ($this->ga4Helper->sendUserIdInEvents() && $userId) {
            $refundEvent->setUserId($userId);
        }
        $refundEvent->setClientId($clientId);
        $refundEvent->setSessionId($gaSessionId);
        $refundEvent->setTimestamp($gaTimestamp);
        $refundEvent->setTransactionId($order->getIncrementId());
        $refundEvent->setAffiliation($this->ga4Helper->getAffiliationName());
        $refundEvent->setCoupon((string)$order->getCouponCode());
        $refundEvent->setValue(number_format($creditmemo->getGrandTotal(), 2, '.', ''));
        $refundEvent->setShipping(number_format($creditmemo->getShippingAmount(), 2, '.', ''));
        $refundEvent->setTax(number_format($creditmemo->getTaxAmount(), 2, '.', ''));
        $refundEvent->setCurrency($currencyCode);

        $items = $creditmemo->getAllItems();

        foreach ($items as $creditmemoItem) {
            $parentItem = $creditmemoItem->getOrderItem()->getParentItem();
            $productType = $creditmemoItem->getOrderItem()->getProductType();

            if ($productType == \Magento\Bundle\Model\Product\Type::TYPE_CODE && !$parentItem) {
                continue;
            }
            if ($parentItem) {
                $parentType = $parentItem->getProductType();
                if (!in_array($parentType, [\Magento\Bundle\Model\Product\Type::TYPE_CODE])) {
                    continue;
                }
            }
            $orderItem = $creditmemoItem->getOrderItem();

            $product = $orderItem->getProduct();
            $productIdModel = $product;
            if ($displayOption == \WeltPixel\GA4\Model\Config\Source\ParentVsChild::CHILD) {
                if ($orderItem->getProductType() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
                    $children = $orderItem->getChildrenItems();
                    foreach ($children as $child) {
                        $productIdModel = $child->getProduct();
                    }
                }
            }



            $productItemOptions = [];
            $productItemOptions['currency'] = $currencyCode;
            $productItemOptions['item_name'] = html_entity_decode($orderItem->getName());
            $productItemOptions['item_id'] = $this->ga4Helper->getGtmProductId($productIdModel);
            $productItemOptions['price'] = number_format($creditmemoItem->getPrice(), 2, '.', '');
            if ($this->ga4Helper->isBrandEnabled()) {
                $productItemOptions['item_brand'] = $this->ga4Helper->getGtmBrand($product);
            }
            if ($this->ga4Helper->isVariantEnabled()) {
                $productOptions = $orderItem->getData('product_options');
                $productType = $orderItem->getData('product_type');
                $variant = $this->ga4Helper->checkVariantForProductOptions($productOptions, $productType);
                if ($variant) {
                    $productItemOptions['item_variant'] = $variant;
                }
            }
            $productCategoryIds = $product->getCategoryIds();
            $categoryName = $this->ga4Helper->getGtmCategoryFromCategoryIds($productCategoryIds);
            $ga4Categories = $this->ga4Helper->getGA4CategoriesFromCategoryIds($productCategoryIds);
            $productItemOptions = array_merge($productItemOptions, $ga4Categories);
            $productItemOptions['item_list_name'] = $categoryName;
            $productItemOptions['item_list_id'] = count($productCategoryIds) ? $productCategoryIds[0] : '';
            $productItemOptions['quantity'] = (double)$creditmemoItem->getQty();

            /**  Set the custom dimensions */
            $customDimensions = $this->dimensionModel->getProductDimensions($product, $this->ga4Helper);
            foreach ($customDimensions as $name => $value) :
                $productItemOptions[$name] = $value;
            endforeach;

            $refundItem = $this->refundItemFactory->create();
            $refundItem->setParams($productItemOptions);

            $refundEvent->addItem($refundItem);

        }

        return $refundEvent;
    }

}
