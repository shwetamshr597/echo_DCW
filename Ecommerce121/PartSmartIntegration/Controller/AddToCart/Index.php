<?php

declare(strict_types=1);

namespace Ecommerce121\PartSmartIntegration\Controller\AddToCart;

use Exception;
use Magento\Framework\App\Action\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Checkout\Model\SessionFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;

class Index extends Action implements HttpPostActionInterface
{
    /**
     * @param Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param SessionFactory $checkoutSession
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(
        Context $context,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly SessionFactory $checkoutSession,
        private readonly CartRepositoryInterface $cartRepository,
    ) {
        parent::__construct($context);
    }

    /**
     * @return void
     * @throws NotFoundException
     */
    public function execute()
    {
        try {
            $sku = $this->getRequest()->getParam('sku');
            $qty = $this->getRequest()->getParam('qty');

            $product = $this->productRepository->get($sku);
            $session = $this->checkoutSession->create();

            /** @var Quote $quote **/
            $quote = $session->getQuote();
            $quote->addProduct($product, $qty);

            $this->cartRepository->save($quote);
            $session->replaceQuote($quote);
            $session->getQuote()->collectTotals();

            $this->messageManager->addSuccessMessage(
                __( 'Product ' . $sku . ' added to cart successfully.')
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('%1', $e->getMessage())
            );
        } catch (Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Error while adding product to cart.'));
        }
    }
}
