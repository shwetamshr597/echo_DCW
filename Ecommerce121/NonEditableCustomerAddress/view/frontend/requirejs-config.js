var config = {
    config: {
        mixins: {
            'Magento_NegotiableQuote/js/view/shipping': {
                'Ecommerce121_NonEditableCustomerAddress/js/view/shipping-mixin': true
            },
            'Magento_Checkout/js/view/billing-address': {
                'Ecommerce121_NonEditableCustomerAddress/js/view/billing-address-mixin': true
            },
        }
    }
};
