<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2023 Amasty (https://www.amasty.com)
 * @package Special Promotions Base for Magento 2
 */

namespace Amasty\Rules\Test\Integration\Model\Discount;

/**
 * phpcs:ignoreFile
 * @codeCoverageIgnore
 */
class BuyxgetnFixpriceTest extends BaseDiscount
{
    /**
     * @var string
     */
    protected $testProductSku = 'simple4';

    /**
     * @var array
     */
    protected $resultCompareDiscountArray = [
        'amount' => 10,
        'baseAmount' => 10,
        'originalAmount' => 10,
        'baseOriginalAmount' => 10
    ];

    /**
     * @magentoDataFixture ../../../../app/code/Amasty/Rules/Test/Integration/_files/quote_with_simple_products.php
     * @magentoDataFixture ../../../../app/code/Amasty/Rules/Test/Integration/_files/cart_rule_buy_x_gey_y_30_discount_amount.php
     */
    public function testCalculateDiscount()
    {
        $this->baseTest();
    }

    /**
     * {@inheritdoc}
     */
    protected function initObjectRule()
    {
        $this->object = $this->objectManager->create(
            \Amasty\Rules\Model\Rule\Action\Discount\BuyxgetnFixprice::class
        );

        return $this;
    }

    /**
     * @param $salesRuleId
     */
    protected function prepareAmastyRulesData($salesRuleId)
    {
        /** @var \Amasty\Rules\Model\Rule $amastyRuleModel */
        $amastyRuleModel = $this->objectManager->create(\Amasty\Rules\Model\Rule::class);
        $amastyRuleModel
            ->setData('salesrule_id', $this->salesRule->getRuleId())
            ->setPriceselector(0)
            ->setPromoSkus('simple4')
            ->setNqty(1)
            ->setSkipRule('')
            ->save();


        $this->salesRule->setData('amrules_rule', $amastyRuleModel);
    }
}
