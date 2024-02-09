<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Special Promotions Base for Magento 2
 */

namespace Amasty\Rules\Model\Rule\Action\Discount;

use Magento\Catalog\Model\Product\Type;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\SalesRule\Model\Rule;
use Magento\SalesRule\Model\Rule\Action\Discount\Data;

/**
 * Amasty Rules calculation by action.
 *
 * @see \Amasty\Rules\Helper\Data::TYPE_EACH_M_AFT_N_FIX
 */
class EachmaftnFixprice extends Eachn
{
    public const RULE_VERSION = '1.0.0';
    public const DEFAULT_SORT_ORDER = 'desc';

    /**
     * @param Rule $rule
     * @param AbstractItem $item
     *
     * @return Data
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _calculate($rule, $item)
    {
        /** @var Data $discountData */
        $discountData = $this->discountFactory->create();
        $allItems = $this->getSortedItems(
            $item->getAddress(),
            $rule,
            $this->getSortOrder($rule, self::DEFAULT_SORT_ORDER)
        );

        $qty = max(0, $rule->getDiscountQty()); // qty should be positive

        if ($qty) {
            $qty = min($qty, count($allItems));
        } else {
            $qty = count($allItems);
        }

        $offset = (int)$rule->getAmrulesRule()->getEachm();

        if ($offset < 0) {
            $offset = 0;
        }

        $offset = min($offset, count($allItems));
        $allItems = array_slice($allItems, $offset);
        $allItems = $this->skipEachN($allItems, $rule);
        $itemsId = $this->getItemsId($allItems);

        /** @var AbstractItem $allItem */
        foreach ($allItems as $allItem) {
            if ($this->isContinueEachmaftnCalculation($item, $itemsId, $allItem, $qty)) {
                $itemQty = $this->getItemQtyToDiscount($item, $itemsId);
                $itemPrice = $this->itemPrice->getItemPrice($item);
                $baseItemPrice = $this->itemPrice->getItemBasePrice($item);
                $itemOriginalPrice = $this->itemPrice->getItemOriginalPrice($item);
                $baseItemOriginalPrice = $this->itemPrice->getItemBaseOriginalPrice($item);
                $ruleDiscount = $rule->getDiscountAmount();
                $parentItem = $item->getParentItem();

                if ($parentItem && $parentItem->getProduct()->getTypeId() === Type::TYPE_BUNDLE) {
                    $ratio = $this->getBundleDiscountCoefficientForFixPrice($rule, $parentItem);
                    $baseAmount = $ratio * $this->validator->getItemBasePrice($item);
                    $quoteAmount = $ratio * $this->validator->getItemPrice($item);
                    $baseOriginalAmount = $baseItemOriginalPrice * $ratio;
                    $originalAmount = $itemOriginalPrice * $ratio;
                    $discountAmount = $parentItem->getPrice() - $ruleDiscount;

                    $this->discountStorage->resolveDiscountAmount(
                        $discountData,
                        (int)$rule->getId(),
                        $discountAmount,
                        count($parentItem->getChildren())
                    );
                } else {
                    $quoteAmount = $this->priceCurrency->convert(
                        $rule->getDiscountAmount(),
                        $item->getQuote()->getStore()
                    );
                    $baseAmount = $baseItemPrice - $ruleDiscount;
                    $originalAmount = $itemOriginalPrice - $ruleDiscount;
                    $baseOriginalAmount = $baseItemOriginalPrice - $quoteAmount;
                    $quoteAmount = $itemPrice - $quoteAmount;
                }

                $discountData->setAmount($itemQty * $quoteAmount);
                $discountData->setBaseAmount($itemQty * $baseAmount);
                $discountData->setOriginalAmount($itemQty * $originalAmount);
                $discountData->setBaseOriginalAmount($itemQty * $baseOriginalAmount);

                $qty--;
            }
        }

        return $discountData;
    }

    /**
     * @param AbstractItem[] $allItems
     * @return float|int
     */
    protected function getBaseSumOfItems(array $allItems)
    {
        $baseSum = 0;
        /** @var AbstractItem $allItem */
        foreach ($allItems as $allItem) {
            $baseSum += $this->validator->getItemBasePrice($allItem);
        }

        return $baseSum;
    }
}
